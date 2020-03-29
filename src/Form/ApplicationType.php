<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;


class ApplicationType extends AbstractType
{
    /**
     * Fonction qui permet de formater le label des champs et le placeholder
     * @param string $label 
     * @param string $placeholder
     * @param array $options 
     * @return array 
     */
    protected function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ], $options);
    }
}
