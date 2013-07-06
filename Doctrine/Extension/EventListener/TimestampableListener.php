<?php

namespace Msi\CmfBundle\Doctrine\Extension\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;

use Msi\CmfBundle\Doctrine\Extension\BaseListener;

class TimestampableListener extends BaseListener
{
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    public function preUpdate(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            $entity->setUpdatedAt(new \DateTime());

            $em = $e->getEntityManager();
            $uow = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    private function isEntitySupported($e)
    {
        $classMetadata = $e->getEntityManager()->getClassMetadata(get_class($e->getEntity()));

        return $this->getClassAnalyzer()->hasTrait($classMetadata->reflClass, 'Msi\CmfBundle\Doctrine\Extension\Model\Timestampable');
    }
}
