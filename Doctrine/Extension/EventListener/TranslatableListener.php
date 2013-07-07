<?php

namespace Msi\CmfBundle\Doctrine\Extension\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Msi\CmfBundle\Doctrine\Extension\BaseListener;

class TranslatableListener extends BaseListener
{
    protected $container;

    // cuz sometimes we need not to enter the request scope ie: in commands
    protected $skipPostLoad;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->skipPostLoad = false;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
        );
    }

    public function postLoad(EventArgs $e)
    {
        $entity = $e->getEntity();

        if ($this->skipPostLoad === false && $this->isEntitySupported($e, 'Msi\CmfBundle\Doctrine\Extension\Model\Translatable')) {
            $entity->setRequestLocale($this->container->get('request')->getLocale());
        }
    }

    public function setSkipPostLoad($skipPostLoad)
    {
        $this->skipPostLoad = $skipPostLoad;

        return $this;
    }
}
