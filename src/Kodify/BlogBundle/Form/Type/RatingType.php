<?php
namespace Kodify\BlogBundle\Form\Type;

use Kodify\BlogBundle\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', new ChoiceType(), ['choices' => Rating::getChoices()])
            ->add('rate', 'submit', ['attr' => ['class' => 'btn btn-success']]);
    }

    public function getName()
    {
        return 'rate';
    }
}