<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => "Titre de l'article",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le titre de l'article"
                    
                ],
                'constraints' => [
                    new Length([
                        'min' => 10,
                        'max' => 50,
                        'minMessage' => "Titre trop court (min 10 caractères)",
                        'maxMessage' => "Titre trop long (max 50 caractères)"
                    ]),
                    new NotBlank([
                        'message' => "Merci de saisir un titre d'article"
                    ])
                ]
            ])
            ->add('contenu', TextareaType::class, [
                'label' => "Contenu de l'article",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le contenu de l'article",
                    'rows' => 10
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir un titre d'article"
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}