<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
    public function forgotten_password(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');

            $em = $doctrine->getManager();
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
    public function reset_password($token, Request $request, UserPasswordHasherInterface $hasher, ManagerRegistry $doctrine)
    {
        if ($request->isMethod('POST')) {
            $em = $doctrine->getManager();

            $user = $em->getRepository(Chercheur::class)->findOneByResetToken($token);

            if ($user === null) {
                $request->getSession()->getFlashBag()->add('error', 'login_page.message.unknown_token');
                return $this->redirectToRoute('home');
            }

            $user->setResetToken(null);
            $user->setPassword($hasher->hashPassword($user, $request->request->get('password')));
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
    public function register(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator, UserPasswordHasherInterface $hasher, ManagerRegistry $doctrine)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('home');
        }

        $em   = $doctrine->getManager();
        $user = new Chercheur;
        $form = $this->createForm(RegisterType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $em->persist($user);

            if ($this->openAccess) {
                $user->setActif(true);

                $request->getSession()->getFlashBag()->add('success', 'login_page.message.account_created');
            } else {
                $admins = $doctrine
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
    public function deleteAccount(Request $request, ManagerRegistry $doctrine, TokenStorageInterface $tokenStorage)
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
        $user = $this->getUser();
        if ($email != $user->getMail()) {
            $request->getSession()->getFlashBag()->add('error', 'pages.messages.invalid_email');
            return $this->redirectToRoute('profile');
        }

        $doctrine->getManager()->remove($user);
        $doctrine->getManager()->flush();

        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirectToRoute('home');
    }
}
