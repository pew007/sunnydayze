<?php

namespace AppBundle\Form;

use AppBundle\Entity\Vendor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $vendorOptions = [
            'class'         => Vendor::class,
            'multiple'      => false,
            'expanded'      => false,
            'choice_label'  => 'name',
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('v')
                                  ->orderBy('v.name', 'ASC');
            },
        ];

        $builder->add('name', TextType::class)
                ->add('sku', TextType::class)
                ->add('cost', NumberType::class)
                ->add('price', NumberType::class)
                ->add('amountAvailable', NumberType::class)
                ->add('description', TextareaType::class)
                ->add('vendor', EntityType::class, $vendorOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\Product'
            ]
        );
    }
}
