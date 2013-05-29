<?php

namespace Msi\CmfBundle\Doctrine\Extension\Uploadable;

interface UploadableInterface
{
    function getUploadFieldNames();

    function getCreatedAt();

    function getUpdatedAt();
}
