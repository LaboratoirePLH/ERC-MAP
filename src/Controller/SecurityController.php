<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var bool
     */
    private $openAccess;

    public function __construct(string $fromEmail, string $fromName, bool $openAccess)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->openAccess = $openAccess;
    }

    /**
     * @Route("/forgotten_password", name="forgotten_password")
     */
    public function forgotten_password(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, TranslatorInterface $translator)
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(Chercheur::class)->createQueryBuilder("e")
                ->where("e.username = :value")
                ->orWhere("e.mail = :value")
                ->setParameter("value", $username)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if ($user === null) {
                $request->getSession()->getFlashBag()->add('error', 'login_page.message.unknown_user');
                return $this->redirectToRoute('forgotten_password');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $em->flush();
            } catch (\Exception $e) {
                $request->getSession()->getFlashBag()->add('warning', $e->getMessage());
                return $this->redirectToRoute('forgotten_password');
            }

            $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message($translator->trans('mails.password_reset.title')))
                ->setFrom([$this->fromEmail => $this->fromName])
                ->setTo($user->getMail())
                ->setBody(
                    $this->renderView(
                        'email/password_reset.html.twig',
                        ['reset_link' => $url]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $request->getSession()->getFlashBag()->add('success', 'login_page.message.reset_link_sent');

            return $this->redirectToRoute('home');
        }
        return $this->render('login/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function reset_password($token, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(Chercheur::class)->findOneByResetToken($token);

            if ($user === null) {
                $request->getSession()->getFlashBag()->add('error', 'login_page.message.unknown_token');
                return $this->redirectToRoute('home');
            }

            $user->setResetToken(null);
            $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'login_page.message.password_reset');

            return $this->redirectToRoute('home');
        }
        return $this->render('login/reset_password.html.twig', [
            'token' => $token
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator, UserPasswordEncoderInterface $encoder)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }

        $em   = $this->getDoctrine()->getManager();
        $user = new Chercheur;
        $form = $this->get('form.factory')->create(RegisterType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em->persist($user);

            if ($this->openAccess) {
                $user->setActif(true);

                $request->getSession()->getFlashBag()->add('success', 'login_page.message.account_created');
            } else {
                $admins = $this->getDoctrine()
                    ->getRepository(Chercheur::class)
                    ->findBy(["role" => "admin", "gestionnaireComptes" => true]);

                if (!count($admins)) {
                    throw new \Exception("No designated account managers");
                }

                $emails = array_map(function ($u) {
                    return $u->getMail();
                }, $admins);

                $mail = (new \Swift_Message($translator->trans('mails.new_account.title')))
                    ->setFrom([$this->fromEmail => $this->fromName])
                    ->setTo($emails)
                    ->setReplyTo($user->getMail())
                    ->setBody(
                        $this->renderView(
                            'email/new_account.html.twig',
                            compact('user')
                        ),
                        'text/html'
                    );

                $mailer->send($mail);

                $request->getSession()->getFlashBag()->add('success', 'login_page.message.account_requested');
            }
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('login/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete_account", name="delete_account")
     */
    public function deleteAccount(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if ($this->isGranted('ROLE_CONTRIBUTOR') || !$request->isMethod('POST')) {
            return $this->redirectToRoute('profile');
        }

        if (!$this->isCsrfTokenValid('delete_account', $request->request->get('token'))) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.deletion_failed_csrf');
            return $this->redirectToRoute('profile');
        }

        $email = $request->request->get('email', '');
        if (!strlen($email)) {
            $request->getSession()->getFlashBag()->add('error', 'pages.messages.email_mandatory');
            return $this->redirectToRoute('profile');
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($email != $user->getMail()) {
            $request->getSession()->getFlashBag()->add('error', 'pages.messages.invalid_email');
            return $this->redirectToRoute('profile');
        }

        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirectToRoute('home');
    }
}
