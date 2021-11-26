<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomComplet', TextType::class,
                ['label' => false,
                    'attr'=>['class'=>'form-control11','placeholder' => 'Votre Nom Complet',
                    ]
                ])
            ->add('email', EmailType::class,
                ['label' => false,
                    'attr'=>['class'=>'form-control1','placeholder' => 'Votre Adresse mail',
                    ]
                ])
            ->add(/** @lang text */ 'telephone', TelType::class,  ['label' => false,
                'attr'=>['class'=>'form-control1','placeholder' => 'Votre Numero De Téléphone',
                ]
            ])
            ->add('nombrePersonne', IntegerType::class,  ['label' => false,
                'attr'=>['class'=>'form-control1','placeholder' => 'Votre Numero De Téléphone',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }


}
