<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class,
                ['label' => 'Votre Nom',
                    'attr'=>['class'=>'form-control','placeholder' => 'Votre Nom',

                    ]
                ])
            ->add('userEmail', EmailType::class,
                ['label' => 'Votre Email' ,
                    'attr'=>['class'=>'form-control',
                        'placeholder' => 'Votre Email'
                    ]
                ])
            ->add('content', TextareaType::class,
                [
                    'label' => 'Votre Commentaire',
                    'attr'=>['class'=>'form-control',
                        'placeholder' => 'Votre Commentaire',
                        "rows"=>"4"
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }
}
