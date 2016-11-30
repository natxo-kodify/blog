<?php

namespace Kodify\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

class PostRatingType extends AbstractType
{
    public function getName()
    {
        return 'post_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'rating',
                'integer',
                array(
                    'constraints' => array(
                        new Range(array('min' => 0, 'max' => 5)),
                    ),
                )
            )
            ->add('rate', 'submit', ['attr' => ['class' => 'btn btn-success']]);
    }
}
