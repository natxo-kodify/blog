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
        $idPost = $options['empty_data']['id'];
        $builder
            ->add('content')
            ->add('author')
            ->add('post', 'entity', array(
                'class' => 'Kodify\BlogBundle\Entity\Post',
                'property' => 'id',
                'query_builder' => function (PostRepository $post_repository) use ($idPost)
                    {
                        return $post_repository->createQueryBuilder('post')
                            ->where("post.id = " . $idPost);
                    }
            ))
            ->add('save', 'submit', ['attr' => ['class' => 'btn btn-success']]);
    }

    public function getName()
    {
        return 'comment';
    }

}