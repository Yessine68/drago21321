<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required'=>True
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Price',
                'required'=>True, 
                'constraints' => [
                    new Range([
                        'min' => 5,
                        'max' => 25,
                        'minMessage' => 'The price should be at least {{ limit }}.',
                        'maxMessage' => 'The price should not exceed {{ limit }}.',
                    ]),
                ],
            ])
            ->add('datefabrication', DateType::class, [
                'label' => 'Date of Fabrication',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'quantite',
                'required'=>True,
                'constraints' => [
                    new Range([
                        'min' => 10,
                        'max' => 100,
                        'minMessage' => 'The price should be at least {{ limit }}.',
                        'maxMessage' => 'The price should not exceed {{ limit }}.',
                    ]),
                ],
            ])
            ->add('image', TextType::class, [
                'label' => 'Product Image',
                'required' => false,
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

