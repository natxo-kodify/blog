<?php

namespace Kodify\BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Form\FormInterface;


abstract class AppService extends ContainerAware
{
    /**
     * Persists the given object
     *
     * @param $entity mixed
     */
    public abstract function persist($entity);

    /**
     * Persists the data available from the given form
     *
     * @param $form FormInterface The form with the data to be persisted
     */
    public function persistForm($form) {
        $this->persist($form->getData());
    }
}
