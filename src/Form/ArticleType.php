<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', textType::class,[
                'label'=> "Titre",
                'attr'=> [
                    'class'=> 'form-control mt-2'
                ]
            ])
            ->add('content', textareaType::class, [
                'label'=> 'commentaire',
                'attr'=> [
                    'class'=> 'form-control mt-2'
                ]
            ])

            ->add('urlImg', FileType::class, [
                'label'=> 'photo',
                'attr'=> [
                'class'=> 'form-control mt-2'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Publier',
                'attr'=> [
                    'class'=> 'btn btn-success mt-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
