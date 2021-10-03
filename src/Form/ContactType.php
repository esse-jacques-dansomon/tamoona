<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'form-control', 'placeholder'=>'votre Nom']
                ])
            ->add('userEmail', EmailType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'form-control ', 'placeholder'=>'votre Email']
                ])
            ->add('phone', TextType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'form-control ', 'placeholder'=>'votre Téléphone']
                ])
            ->add('subject', TextType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'form-control ', 'placeholder'=>'Sujet']
                ])
            ->add('message', TextareaType::class,
                [
                    'label' => false,
                    'attr' => ['class' => 'form-control ', 'placeholder'=>'Votre Message', 'rows' => 5,]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
