<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType {

    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * 
     * @return array
     */
    protected function getConfiguration($label, $placeholder, $options = [])
    {
        // array_merge() permet de fusionner le tableau de base avec le tableau des éventuelles options contenu dans la variable $options
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}