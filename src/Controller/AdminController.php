<?php

namespace App\Controller;

use App\Entity\Chercheur;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends EasyAdminController
{
    private $passwordHasher;
    private $translator;
    private $mailer;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    public function __construct(UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, \Swift_Mailer $mailer, string $fromEmail, string $fromName)
    {
        $this->passwordHasher = $passwordHasher;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    protected function persistChercheurEntity(Chercheur $user)
    {
        if (!!$user->getNewPassword()) {
            $encodedPassword = $this->encodePassword($user, $user->getNewPassword());
            $user->setPassword($encodedPassword);
        }
        $this->persistEntity($user);
    }

    protected function updateChercheurEntity(Chercheur $user)
    {
        if (!!$user->getNewPassword()) {
            $encodedPassword = $this->encodePassword($user, $user->getNewPassword());
            $user->setPassword($encodedPassword);
        }
        $this->updateEntity($user);
    }

    private function encodePassword($user, $password)
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    protected function createNewFormuleEntity()
    {
        $formule = new \App\Entity\Formule();
        $formule->setCreateur(
            $this->getUser()
        );
        return $formule;
    }

    public function validateUserAction()
    {
        $id = $this->request->query->get('id');
        $user = $this->em->getRepository(Chercheur::class)->activate($id);
        $this->em->flush();

        // Send email notification that account was activated by administrators
        $mail = (new \Swift_Message($this->translator->trans('mails.account_activated.title')))
            ->setFrom([$this->fromEmail => $this->fromName])
            ->setTo($user->getMail())
            ->setReplyTo($this->fromEmail)
            ->setBody(
                $this->renderView(
                    'email/account_activated.html.twig',
                    compact('user')
                ),
                'text/html'
            );

        $this->mailer->send($mail);

        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ]);
    }
}
