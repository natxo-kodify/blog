<?php

namespace Kodify\BlogBundle\Tests\Form\Type;

use Kodify\BlogBundle\Form\Type\AuthorType;
use Kodify\BlogBundle\Entity\Author;
use Kodify\BlogBundle\Model\Command\CreateAuthorCommand;
use Symfony\Component\Form\Test\TypeTestCase;

class AuthorTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'test',
        ];
        $type     = new AuthorType();
        $form     = $this->factory->create($type);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        /** @var CreateAuthorCommand $createAuthorCommand */
        $createAuthorCommand = $form->getData();
        $this->assertInstanceOf('Kodify\BlogBundle\Model\Command\CreateAuthorCommand', $createAuthorCommand);
        $this->assertEquals($formData['name'], $createAuthorCommand->getName());
        $view     = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
