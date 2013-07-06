<?php

namespace Msi\CmfBundle\Doctrine\Extension;

use Doctrine\Common\EventSubscriber;
use Msi\CmfBundle\Tools\ClassAnalyzer;

abstract class BaseListener implements EventSubscriber
{
    public function __construct()
    {
        $this->classAnalyzer = new ClassAnalyzer;
    }

    public function getClassAnalyzer()
    {
        return $this->classAnalyzer;
    }

    abstract public function getSubscribedEvents();
}
