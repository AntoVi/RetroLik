<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['commentBack'] == true)
        {
            $builder
            ->add('auteur', TextType::class, [
                'label' => "Votre nom/pseudo",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir votre nom/pseudo"
                    
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 50,
                        'minMessage' => "Nom/pseudo trop court (min 5 caractères)",
                        'maxMessage' => "Nom/pseudo trop long (max 50 caractères)"
                    ]),
                    new NotBlank([
                        'message' => "Merci de saisir un nom/pseudo"
                    ])
                ]
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => "Commentaire",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le contenu du commentaire",
                    'rows' => 50
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir un commentaire"
                    ]),
                    new Length([
                        'max' => 300,
                        'maxMessage' => "La limite de caractère a été dépassée"
                    ])
                ]
            ]);
        }
        elseif($options['commentFront'] == true)
        {   
            $builder
            ->add('commentaire', TextareaType::class, [
                'label' => "Commentaire",
                'required' => false,
                'attr' => [
                    'placeholder' => "Saisir le contenu du commentaire",
                    'rows' => 5
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de saisir un commentaire"
                    ])
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'commentBack' => false, 
            'commentFront' => false
        ]);
    }
}