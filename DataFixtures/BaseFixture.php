<?php

namespace Msi\CmfBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class BaseFixture extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function create(array $values = [], array $translations = [], $reference = null)
    {
        $entity = $this->manager->create();

        foreach ($values as $key => $value) {
            $setter = 'set'.ucfirst($key);
            $entity->$setter($value);
        }

        if (count($translations)) {
            $this->manager->createTranslations($entity, array_keys($translations));
        }

        foreach ($translations as $locale => $val) {
            foreach ($val as $key => $value) {
                $setter = 'set'.ucfirst($key);
                $entity->getTranslation($locale)->$setter($value);
            }
        }

        if (null !== $reference) {
            $this->addReference($reference, $entity);
        }

        $this->manager->update($entity);
    }

    public function mergeAndGetReference($name)
    {
        return $this->manager->merge($this->getReference($name))
    }

    public function getOrder()
    {
        return 1;
    }
}
