<?php

namespace Msi\CmfBundle\Doctrine\Extension\Uploadable;

interface UploadableInterface
{
    function getUploadFields();

    function getCreatedAt();

    function getUpdatedAt();
}
