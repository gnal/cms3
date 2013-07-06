<?php

namespace Msi\CmfBundle\Doctrine\Extension\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;

use Msi\CmfBundle\Doctrine\Extension\BaseListener;

class UploadableListener extends BaseListener
{
    private $uploader;

    public function __construct($uploader)
    {
        parent::__construct();
        $this->uploader = $uploader;
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        );
    }

    public function prePersist(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            $this->uploader->preUpload($entity);
        }
    }

    public function preUpdate(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            $this->uploader->preUpload($entity);
            $em   = $e->getEntityManager();
            $uow  = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($entity));
            $uow->recomputeSingleEntityChangeSet($meta, $entity);
        }
    }

    public function postPersist(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            $this->uploader->postUpload($entity);
        }
    }

    public function postUpdate(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            $this->uploader->postUpload($entity);
        }
    }

    public function postRemove(EventArgs $e)
    {
        $entity = $e->getEntity();
        if ($this->isEntitySupported($e)) {
            foreach ($entity->getUploadFields() as $fieldName) {
                $this->uploader->removeUpload($fieldName, $entity);
            }
        }
    }

    private function isEntitySupported($e)
    {
        $classMetadata = $e->getEntityManager()->getClassMetadata(get_class($e->getEntity()));

        return $this->getClassAnalyzer()->hasTrait($classMetadata->reflClass, 'Msi\CmfBundle\Doctrine\Extension\Model\Uploadable');
    }
}
