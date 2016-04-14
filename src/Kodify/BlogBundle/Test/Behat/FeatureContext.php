<?php

namespace Kodify\BlogBundle\Test\Behat;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class FeatureContext extends RawMinkContext
{
    use KernelDictionary;

    /**
     * @param string $serviceName
     * @return mixed
     */
    protected function getService($serviceName)
    {
        return $this->getContainer()->get($serviceName);
    }

    /**
     * @param string $className
     * @return mixed
     */
    protected function getRepository($className)
    {
        $em = $this->getService('doctrine.orm.entity_manager');

        return $em->getRepository($className);
    }
}
