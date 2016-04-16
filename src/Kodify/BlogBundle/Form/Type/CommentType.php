<?php
namespace Kodify\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', new TextareaType())
            ->add('author')
            ->add('publish', 'submit', ['attr' => ['class' => 'btn btn-success']]);
    }

    public function getName()
    {
        return 'comment';
    }
}