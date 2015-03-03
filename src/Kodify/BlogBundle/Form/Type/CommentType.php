<?php
namespace Kodify\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
            ->add('text')
            ->add('author')
			->add('post')
			//->add('post', 'entity',['class' => 'KodifyBlogBundle:Post','property'=>'id'] )
            ->add('save', 'submit', ['attr' => ['class' => 'btn btn-success'],'label'=>'Publish']);
    }

    public function getName()
    {
        return 'comment';
    }
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'Kodify\BlogBundle\Entity\Comment',
	    ));
	}
}