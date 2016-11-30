<?php
namespace Kodify\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Kodify\BlogBundle\Repository\PostRepository;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $builder->getAttribute('data_collector/passed_options');
        $post_id = $options['data']['post_id'];

        $builder
            ->add('text')
            ->add('author')
            ->add('post', 'entity', array(
                'class'         => 'Kodify\BlogBundle\Entity\Post',
                'property'      => 'id',
                'query_builder' => function (PostRepository $post_repository) use ($post_id) {
                    return $post_repository->createQueryBuilder('post')
                        ->where('post.id = ' . $post_id);
                },
                'read_only' => true,
            ))
            ->add('save', 'submit', ['attr' => ['class' => 'btn btn-success']]);
    }

    public function getName()
    {
        return 'comment';
    }
}
