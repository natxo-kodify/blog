<?php
namespace Kodify\BlogBundle\Tests\Form\Type;

use Kodify\BlogBundle\Form\Type\CommentType;
use Kodify\BlogBundle\Entity\Comment;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        //date_default_timezone_set( 'Europe/Paris' );

        $formData = array(
            'text'   => 'test',
            'author'  => 1,
        );
        $type     = new CommentType();
        $form     = $this->factory->create($type);

        $object = new Comment();
        $object->setText('test');
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        $view     = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
