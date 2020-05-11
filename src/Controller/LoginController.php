<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Form\LoginType;
use App\Form\RegisterType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AbstractController
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
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->get('form.factory')->create(LoginType::class);

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
            'form'          => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function login_check()
    {
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }

        $em   = $this->getDoctrine()->getManager();
        $user = new Chercheur;
        $form = $this->get('form.factory')->create(RegisterType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
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
}
