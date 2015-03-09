<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use IDCI\Bundle\StepBundle\Path\PathInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

class LinkPathType extends EndPathType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('href'))
            ->setDefaults(array(
                'type' => 'idci_step_path_form_link',
            ))
            ->setNormalizers(array(
                'label' => function(Options $options, $value) {
                    if (in_array($value, array(null, 'end'))) {
                        return $options['href'];
                    }

                    return $value;
                }
            ))
            ->setAllowedTypes(array(
                'href' => array('string'),
            ))
        ;
    }
}