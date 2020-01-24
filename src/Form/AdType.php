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

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre", "Titre de votre annonce")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web", "Adresse web de votre annonce (automatique)",
                [
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfiguration("URL de l'image principale", "Donnez l'adresse URL d'une photo de votre bien")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration("Introduction", "Description globale du bien que vous louez")
            )
            ->add(
                'content',
                TextareaType::class,
                $this->getConfiguration("Description détaillée", "Pourquoi aurions-nous envie de dormir chez vous !?")
            )
            ->add(
                'rooms',
                IntegerType::class,
                $this->getConfiguration("Nombre de chambre(s)", "Nombre de chambre(s) disponible(s)")
            )
            ->add(
                'price',
                MoneyType::class,
                $this->getConfiguration("Prix par nuit", "Indiquez le prix souhaité pour une nuit passée dans votre logement")
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'label' => 'Image(s)',
                    'entry_type' => ImageType::class,
                    /* L'option 'allow_add' permet d'autoriser ou non l'ajout d'une entrée pour un champ (ici le champ 'images') -> cela génère par la même occasion le code HTML nécessaire à l'inclusion 
                    d'une nouvelle entrée et le stocke dans une variable nommée "data-prototype" */
                    'allow_add' => true,
                    'allow_delete' => true
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
