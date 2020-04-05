<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstName', 
            TextType::class, 
            $this->getConfiguration("Prénom", "Votre prénom ...")
        )
        ->add('lastName', 
            TextType::class, 
            $this->getConfiguration("Nom", "Votre nom de famille ...")
        )   
        ->add('email', 
            EmailType::class,
            $this->getConfiguration("Email", "Votre email ...")
        )
        ->add('picture', 
            UrlType::class, 
            $this->getConfiguration("Avatar", "URL de votre avatar ...")
        ) 
        ->add('introduction', 
            TextType::class, 
            $this->getConfiguration("Introduction", "Entrer une courte déscritpion de vous ...")
        )
        ->add('description', 
            TextareaType::class, 
            $this->getConfiguration("Description", "Présentez-vous...", [
                'attr' => [
                    'rows' => 9
                    ]
                ])
                );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
