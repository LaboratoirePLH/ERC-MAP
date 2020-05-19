<?php

namespace App\Form;

use App\Entity\Chercheur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'    => 'login_page.form.username',
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'repeat_password_error',
                'options'         => ['attr' => ['class' => 'password-field']],
                'required'        => true,
                'first_options'   => ['label' => 'login_page.form.password'],
                'second_options'  => ['label' => 'login_page.form.password_confirmation'],
            ])
            ->add('mail', TextType::class, [
                'label'    => 'chercheur.fields.mail',
                'required' => true
            ])
            ->add('prenomNom', TextType::class, [
                'label'    => 'chercheur.fields.nom',
                'required' => true
            ])
            ->add('institution', TextType::class, [
                'label'    => 'chercheur.fields.institution',
                'required' => true
            ])
            ->add('g-recaptcha-response', EWZRecaptchaType::class, [
                'mapped' => false,
                'constraints' => [new RecaptchaTrue(["message" => "login_page.message.invalid_captcha"])]
            ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return 'register';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chercheur::class,
        ]);
    }
}
