<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class JeuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => "Nom du jeu",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le nom du jeu"
                    
                ],
                'constraints' => [
                    new Length([
                        'max' => 18,
                        'maxMessage' => "Nom trop long (max 18 caractères)"
                    ]),
                    new NotBlank([
                        'message' => "Merci de saisir le nom du jeu"
                    ])
                ]
            ])
            ->add('Img',FileType::class, [
                'label' => "Uploader une image",
                'mapped' => true, // signifie que le champ est associé à une propriété et 
                // qu'il sera inséré en BDD 
                'required' => false, 
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Formats autorisés : jpg/jpeg/png'
                    ])
                ]
            ])
            // On définit le champ permettant d'associer une catégorie à l'article dans le formulaire
            // Ce champ provient d'une autre entité, en gros c la clé étrangère
            ->add('Category', EntityType::class, [
                'label' => "Choisir une catégorie",
                'class' => Category::class, // On précise de quelle entité vient de ce champ
                'choice_label' => 'nom' // on définit la valeur qui apparaitra dans la liste déroulante
            ])
            ->add('lien', TextType::class, [
                'label' => "Indiquer le lien vers le jeu",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le lien vers le jeu"
                ],
                'constraints' => [
                    new notBlank([
                        'message' => "Merci de saisir un lien"
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jeux::class,
        ]);
    }
}