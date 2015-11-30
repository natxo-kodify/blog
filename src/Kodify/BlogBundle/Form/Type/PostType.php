<?php
namespace Kodify\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('author', 'entity', [
                'class' => 'Kodify\BlogBundle\Entity\Author'
            ])
            ->add('save', 'submit', ['attr' => ['class' => 'btn btn-success']]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
           'data_class' => '\Kodify\BlogBundle\Model\Command\CreatePostCommand'
        ]);
    }

    public function getName()
    {
        return 'post';
    }
}