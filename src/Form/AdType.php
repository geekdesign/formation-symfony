<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType  extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', 
                TextType::class, 
                $this->getConfiguration("Titre", "Tapez un titre pour l'annonce")
            )
            ->add(
                'slug', 
                TextType::class, 
                $this->getConfiguration("URL de l'article", "Ce champs est automatique mais vous pouvez le surchargé", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage', 
                UrlType::class, 
                $this->getConfiguration("URL de l'image", "Insérer le lien de l'image de couverture")
            )
            ->add(
                'introduction', 
                TextType::class,  
                $this->getConfiguration("Introduction", "Taper l'introduction de votre annonce")
            )
            ->add(
                'content', 
                TextareaType::class,  
                $this->getConfiguration("Contenu", "Taper le contenu de l'annonce", [
                    'attr' => [
                        'rows' => 8
                    ]
                ])
            )
            ->add(
                'rooms', 
                IntegerType::class, 
                $this->getConfiguration("Nombre de chambres", "Insérer le nombre de chambre")
            )
            ->add(
                'price', 
                MoneyType::class, [
                    'label' => 'Prix / nuit',
                    'currency' => 'CHF',
                    'attr' => [
                        'placeholder' => 'Inséréer le montant par nuit'
                    ]
                ]
            )
            ->add(
                'images',
                CollectionType::class, [
                    'entry_type' => ImageType::class, 
                    'allow_add' => true,
                    'allow_delete' => true,

                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
