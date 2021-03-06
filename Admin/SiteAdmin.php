<?php

namespace Msi\CmfBundle\Admin;

use Msi\CmfBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class SiteAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'form_template' => 'MsiCmfBundle:Site:form.html.twig',
            'sidebar_nav_template' => 'MsiCmfBundle:Site:sidebar_nav.html.twig',
            'search_fields' => ['a.id', 'a.host', 't.brand'],
        ];
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('enabled', 'boolean')
            ->add('brand')
            ->add('host')
            ->add('', 'action')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $choices = [];
        foreach ($this->container->getParameter('msi_cmf.app_locales') as $locale) {
            $choices[$locale] = strtoupper($locale);
        }

        $builder
            ->add('host', 'text', [
                'attr' => [
                    'data-help' => 'Pro tip: Enter the correct host name instead of relying of the "isDefault" field to reduce number of database queries.',
                ],
            ])
            ->add('isDefault')
            ->add('locale', 'choice', [
                'choices' => $choices,
                'label' => 'Default language',

            ])
            ->add('locales', 'choice', [
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
                'label' => 'Available languages',

            ])
            ->add('css', 'textarea')
            ->add('js', 'textarea')
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('brand')
            ->add('offlineMessage', 'textarea')
            ->add('metaKeywords', 'textarea')
            ->add('metaDescription', 'textarea')
        ;
    }
}
