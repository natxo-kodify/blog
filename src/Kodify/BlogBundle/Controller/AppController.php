<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/04/16
 * Time: 19:06
 */

namespace Kodify\BlogBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;


class AppController extends Controller
{
    /**
     * Names of the services the controller uses
     *
     * @var string[]
     */
    protected $services;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        foreach ($this->services as $service) {
            $this->get($service)->setContainer($container);
        }
    }

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