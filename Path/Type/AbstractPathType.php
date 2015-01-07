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
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractPathType implements PathTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'next_options' => array(
                    'label' => 'Next >'
                ),
                'listeners'    => array(
                    'before' => array(),
                    'after'  => array()
                ),
            ))
            ->setAllowedTypes(array(
                'next_options' => 'array',
                'listeners'    => 'array',
            ))
            ->setAllowedValues(array(
                'listeners' => function ($value) {
                    if (!is_array($value)) {
                        return false;
                    }
                    if (count($value) >= 3) {
                        return false;
                    }

                    foreach ($value as $key => $val) {
                        if (!in_array($key, array('before', 'after'))) {
                            return false;
                        }

                        if (!is_array($val)) {
                            return false;
                        }
                    }

                    return true;
                },
            ))
            ->setNormalizers(array(
                'listeners' => function (Options $options, $value) {
                    $formattedValue = array(
                        'before' => array(),
                        'after' => array()
                    );

                    foreach ($value as $time => $listeners) {
                        foreach ($value[$time] as $key => $val) {
                            if (is_array($val)) {
                                // Handle case ['alias' => array(...)].
                                if (is_string($key)) {
                                    $val['alias'] = $key;
                                    $formattedValue[$time][] = $val;
                                // Handle case [0 => array('alias' => '', ...)].
                                } else {
                                    $formattedValue[$time][] = $val;
                                }
                            // Handle case [0 => 'alias'].
                            } else {
                                $formattedValue[$time][] = array('alias' => $val);
                            }
                        }
                    }

                    return $formattedValue;
                },
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(PathInterface $path, MapInterface $map, array $options = array())
    {
        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator)
    {
        return null;
    }
}
