<?php
namespace Kodify\BlogBundle\Tests\Form\Type;

use Kodify\BlogBundle\Entity\Comment;
use Kodify\BlogBundle\Form\Type\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'content'   => 'test comment adsdsds',
            'author'    => 1,
            'post'      => 2,
        );
        $type     = new CommentType();
        $form     = $this->factory->create($type);

        $object = new Comment();
        $object->setContent('test comment adsdsds');
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
