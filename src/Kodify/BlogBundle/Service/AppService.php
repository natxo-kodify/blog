<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/04/16
 * Time: 19:06
 */

namespace Kodify\BlogBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerAware;


class AppService extends ContainerAware
{
    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }
}