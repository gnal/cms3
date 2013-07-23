<?php

namespace Msi\CmfBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class CmfLoader implements LoaderInterface
{
    private $loaded = false;
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $collection = new RouteCollection();

        foreach ($this->container->getParameter('msi_cmf.admin_ids') as $id) {
            $collection->addCollection($this->buildRoutes($id));
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'msi_cmf' === $type;
    }

    public function getResolver()
    {
    }

    public function setResolver(LoaderResolverInterface $resolver)
    {
    }

    protected function buildRoutes($id)
    {
        $collection = new RouteCollection();

        $namespace = preg_replace(['|^[a-z]+_[a-z]+_|', '|_admin$|', '|_|'], ['', '', '-'], $id);

        $prefix = '/admin/'.$namespace;
        $suffix = '';

        $names = array(
            'list',
            'new',
            'edit',
            'delete',
            'toggle',
            'sort',
            'deleteUpload',
        );

        foreach ($names as $name) {
            $collection->add(
                $id.'_'.$name,
                new Route(
                    $prefix.'/'.$name.$suffix,
                    array(
                        '_controller' => 'MsiCmfBundle:Core:'.$name,
                        '_admin' => $id,
                    )
                )
            );
        }

        $collection->add(
            $id.'_list',
            new Route(
                $prefix.'/list'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:list',
                    '_admin' => $id,
                ),
                array(
                    '_method' => 'GET',
                )
            )
        );

        $collection->add(
            $id.'_new',
            new Route(
                $prefix.'/new'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:new',
                    '_admin' => $id,
                ),
                array(
                    '_method' => 'GET|POST',
                )
            )
        );

        $collection->add(
            $id.'_edit',
            new Route(
                $prefix.'/{id}/edit'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:edit',
                    '_admin' => $id,
                )
            )
        );

        $collection->add(
            $id.'_delete',
            new Route(
                $prefix.'/{id}/delete'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:delete',
                    '_admin' => $id,
                )
            )
        );

        $collection->add(
            $id.'_softDelete',
            new Route(
                $prefix.'/{id}/soft-delete'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:softDelete',
                    '_admin' => $id,
                )
            )
        );

        $collection->add(
            $id.'_toggle',
            new Route(
                $prefix.'/{id}/toggle'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:toggle',
                    '_admin' => $id,
                )
            )
        );

        $collection->add(
            $id.'_deleteUpload',
            new Route(
                $prefix.'/{id}/delete-upload'.$suffix,
                array(
                    '_controller' => 'MsiCmfBundle:Core:deleteUpload',
                    '_admin' => $id,
                )
            )
        );

        return $collection;
    }
}
