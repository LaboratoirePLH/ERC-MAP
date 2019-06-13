<?php

namespace App\Form;

use App\Entity\Chercheur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, ['label' => 'login_page.form.username'])
            ->add('_password', PasswordType::class, ['label' => 'login_page.form.password'])
            ->add('login', SubmitType::class, ['label' => 'login_page.form.login'])
        ;
    }

    public function getBlockPrefix() {}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chercheur::class,
        ]);
    }
}
