<?php

namespace Msi\CmfBundle\Doctrine\Extension\Model;

trait Translatable
{
    protected $requestLocale;

    protected $translations;

    public function getTranslation($locale = null)
    {
        if ($this->translations->count() === 0) {
            throw new \Exception('A translatable entity must have at least one translation. Translatable entity '.get_class($this).' has no translation.');
        }

        if (null !== $locale) {
            foreach ($this->translations as $translation) {
                if ($locale === $translation->getLocale()) {
                    return $translation;
                }
            }
        }

        foreach ($this->translations as $translation) {
            if ($this->requestLocale === $translation->getLocale()) {
                return $translation;
            }
        }

        return $this->translations->first();
    }

    public function hasTranslation($locale)
    {
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLocale() === $locale) {
                return true;
            }
        }

        return false;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    public function getRequestLocale()
    {
        return $this->requestLocale;
    }

    public function setRequestLocale($requestLocale)
    {
        $this->requestLocale = $requestLocale;

        return $this;
    }
}
