<?php

namespace App\Form;

use App\Entity\Chercheur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, ['label' => 'login_page.form.username'])
            ->add('_password', PasswordType::class, ['label' => 'login_page.form.password'])
            ->add('_remember_me', CheckboxType::class, [
                'label'    => 'login_page.form.remember_me',
                'required' => false,
            ])
            ->add('recaptcha', EWZRecaptchaType::class, ['mapped' => false])
        ;
    }

    public function getBlockPrefix() { return ''; }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chercheur::class,
        ]);
    }
}
