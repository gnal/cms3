<?php

namespace Msi\CmfBundle\Doctrine\Extension\Uploadable\Traits;

trait UploadableEntity
{
    public function getUploadDir($fieldName)
    {
        if (!in_array($fieldName, $this->getUploadFieldNames())) {
            throw new \InvalidArgumentException('upload field name "'.$fieldName.'" doesn\'t exist for entity '.get_class($this));
        }

        $class = get_class($this);
        $class = substr($class, strrpos($class, '\\') + 1);

        return strtolower($class.'-'.$fieldName);
    }

    public function getPathname($fieldName, $prefix = '')
    {
        if (!in_array($fieldName, $this->getUploadFieldNames())) {
            throw new \InvalidArgumentException('upload field name "'.$fieldName.'" doesn\'t exist for entity '.get_class($this));
        }

        $getter = 'get'.ucfirst($fieldName);

        return '/uploads/'.$this->getUploadDir($fieldName).'/'.$prefix.$this->$getter();
    }
}
