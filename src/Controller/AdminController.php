<?php

namespace App\Controller;

use App\Entity\Chercheur;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends EasyAdminController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    protected function createNewFormuleEntity()
    {
        $formule = new \App\Entity\Formule();
        $formule->setCreateur(
            $this->get('security.token_storage')->getToken()->getUser()
        );
        return $formule;
    }
}
