<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'required' => false,
                'attr'=>  [
                    'placeholder' => "Saisir le nom de la catégorie"
                ],
                "constraints" => [
                    new NotBlank([
                        'message' => "Merci de saisir un nom à la catégorie."
                    ]),
                    new Length([
                        'max' => 18, 
                        'maxMessage' => "Nom trop long (18 caractères max)."
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
