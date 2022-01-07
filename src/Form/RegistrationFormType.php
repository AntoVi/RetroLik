<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['userRegister'] == true)
        {
            $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir une adresse email."
                ])
            ])
            ->add('nom', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir votre nom."
                ])
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir votre prenom."
                ])
            ])
            ->add('pseudo', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir un pseudo."
                ])
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' => "Les mots de passes ne correspondent pas",
                'options' => [
                    'attr' => [
                        'class' => 'password-field'
                    ]
                ],
                'first_options' => [
                    'label' => "Mot de passe"
                ],
                'second_options' => [
                    'label' => "Confirmer votre mot de passe"
                ],
                'constraints' => [
                    new NotBlank([
                    'message' => "Merci de saisir votre mot de passe."
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => "Votre mot de passe ne comporte pas assez de caractères"
                    ])
                ]
            ])
        ;
        }
        elseif($options['profilUpdate'] == true) 
        {
            $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir une adresse email."
                ])
            ])
            ->add('nom', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir votre nom."
                ])
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir votre prenom."
                ])
            ])
            ->add('pseudo', TextType::class, [
                'required' => false,
                'constraints' => new NotBlank([
                    'message' => "Merci de saisir un pseudo."
                ])
             ])

             ->add('Avatar',FileType::class, [
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
            ]);
        }

        elseif ($options['userBack'] == true) 
        {
           // Nous definissons le champ 'roles' en collectionType afin de renvoyer
           // dans la bdd un array ChoiceType permet de définir une liste déroulante
           // entry_options permet de définir les options du selecteur
           $builder
           ->add('roles', ChoiceType::class, [
               'choices' => [
                   'Utilisateur' => '',
                   'Administrateur' => 'ROLE_ADMIN'
               ],
               'expanded' => true,
               'multiple' => true,
               'label' => "Définir le rôle de l'utilisateur"
           ]);
            
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'userRegister' => false,
            'profilUpdate' => false,
            'userBack' => false
        ]);
    }
}