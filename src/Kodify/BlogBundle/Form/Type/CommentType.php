<?php

namespace Kodify\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
            ->add('author')
            ->add('post', 'hidden')
            ->add('publish', 'submit', ['attr' => ['class' => 'btn btn-success']])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'comment';
    }
}
