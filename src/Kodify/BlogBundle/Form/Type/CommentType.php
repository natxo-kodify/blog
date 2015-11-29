<?php
/**
 * Created by PhpStorm.
 * User: adrianr
 * Date: 29/11/15
 * Time: 0:09
 */

namespace Kodify\BlogBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('author', 'entity', ['class' => 'Kodify\BlogBundle\Entity\Author'])
            ->add('text', 'text')
            ->add('save', 'submit', ['attr' => ['class' => 'btn btn-success', 'value' => 'Publish']]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'comment';
    }
}