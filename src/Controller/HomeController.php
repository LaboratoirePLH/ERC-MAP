<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Entity\Source;
use App\Entity\Attestation;
use App\Entity\Element;
use App\Form\ChercheurType;
use App\Form\ChangePasswordType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    public function __construct(string $fromEmail, string $fromName)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sourceCount = $this->getDoctrine()
            ->getRepository(Source::class)
            ->createQueryBuilder('s')
            ->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $attestationCount = $this->getDoctrine()
            ->getRepository(Attestation::class)
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $elementCount = $this->getDoctrine()
            ->getRepository(Element::class)
            ->createQueryBuilder('e')
            ->select('count(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user_name' => $user->getPrenomNom(),
            'counters' => [
                'source'      => $sourceCount,
                'attestation' => $attestationCount,
                'element'     => $elementCount,
            ]
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get("name");
            $email = $request->request->get("email");
            $object = $request->request->get("object");
            $refers_to = $request->request->get("refers_to");
            $refers_to_id = $request->request->get("refers_to_id");
            $message = $request->request->get("message");

            $mail = (new \Swift_Message($translator->trans('mails.contact.title')))
                ->setFrom([$this->fromEmail => $this->fromName])
                ->setTo($this->fromEmail)
                ->setReplyTo($email)
                ->setBody(
                    $this->renderView(
                        'email/contact.html.twig',
                        compact("name", "email", "object", "refers_to", "refers_to_id", "message")
                    ),
                    'text/html'
                );

            $mailer->send($mail);

            $request->getSession()->getFlashBag()->add('success', 'login_page.message.message_sent');

            return $this->redirectToRoute('home');
        }

        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'nav.contact']
            ]
        ]);
    }

    /**
     * @Route("/language/{lang}", name="language")
     */
    public function language($lang, Request $request)
    {
        if (!in_array($lang, ["fr", "en"])) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.invalid_locale');
        } else {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->setPreferenceLangue($lang);
            $this->getDoctrine()->getManager()->flush();
            $request->getSession()->set('_locale', $lang);
        }
        $referer = $request->headers->get('referer');
        if ($referer == NULL) {
            $url = $this->generateUrl('home');
        } else {
            $url = $referer;
        }
        return $this->redirect($url);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, TranslatorInterface $translator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $accountForm = $this->get('form.factory')->create(ChercheurType::class, $user);
        $accountForm->handleRequest($request);
        $passwordForm = $this->get('form.factory')->create(ChangePasswordType::class, null, [
            'repeat_error' => $translator->trans('chercheur.repeat_password_error')
        ]);
        $passwordForm->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($accountForm->isSubmitted() && $accountForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                // Message de confirmation
                $request->getSession()->getFlashBag()->add('success', 'chercheur.profile_edited');
                return $this->redirectToRoute('home');
            }
            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
                if (password_verify($passwordForm['password']->getData(), $user->getPassword())) {
                    $newPassword = password_hash(
                        $passwordForm['new_password']->getData(),
                        PASSWORD_BCRYPT,
                        ['cost' => 15]
                    );
                    $user->setPassword($newPassword);
                    $this->getDoctrine()->getManager()->flush();

                    $request->getSession()->getFlashBag()->add('success', 'chercheur.password_edited');
                    return $this->redirectToRoute('home');
                } else {
                    $passwordForm->get('password')
                        ->addError(
                            new FormError($translator->trans('chercheur.incorrect_password'))
                        );
                    $request->getSession()->getFlashBag()->add('error', 'chercheur.incorrect_password_error');
                }
            }
        }

        return $this->render('home/profile.html.twig', [
            'controller_name' => 'HomeController',
            'locale'          => $request->getLocale(),
            'accountForm'     => $accountForm->createView(),
            'passwordForm'    => $passwordForm->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'chercheur.profile']
            ]
        ]);
    }
}
