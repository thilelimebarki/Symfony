<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => "Email",
                'attr' => [
                    'class' => 'form-control mt-2'
                    ]
            ])
            ->add('password', PasswordType::class,[
                'label' => "Mot de passe",
                'attr' => [
                    'class' => 'form-control mt-2'
                    ]
            ])
            ->add('save', SubmitType::class,[
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-success'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
