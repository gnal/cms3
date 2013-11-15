<?php

namespace Msi\CmfBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Msi\CmfBundle\DependencyInjection\Compiler\FindAdminPass;

class MsiCmfBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FindAdminPass());
    }
}
