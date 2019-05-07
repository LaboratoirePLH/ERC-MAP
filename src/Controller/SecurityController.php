<?php

namespace App\Controller;

use App\Entity\Chercheur;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SecurityController extends Controller
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
     * @Route("/forgotten_password", name="forgotten_password")
     */
    public function forgotten_password(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, TranslatorInterface $translator)
    {
        if ($request->isMethod('POST'))
        {
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

            try{
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
        if ($request->isMethod('POST'))
        {
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
}
