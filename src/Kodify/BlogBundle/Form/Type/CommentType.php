<?php

namespace Kodify\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', 'entity', ['class' => 'Kodify\BlogBundle\Entity\Author'])
            ->add('text', 'text')
            ->add('publish', 'submit', ['attr' => ['class' => 'btn btn-success', 'value' => 'Publish']]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'comment';
    }
}