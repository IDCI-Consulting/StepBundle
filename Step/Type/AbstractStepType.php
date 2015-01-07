<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Step\StepInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;

abstract class AbstractStepType implements StepTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'title'            => null,
                'description'      => null,
                'is_first'         => false,
                'previous_options' => array(
                    'label' => '< Previous'
                ),
                'listeners'        => array(
                    'before' => array(),
                    'after'  => array()
                ),
            ))
            ->setAllowedTypes(array(
                'title'            => array('null', 'string'),
                'description'      => array('null', 'string'),
                'is_first'         => array('bool'),
                'previous_options' => array('array'),
                'listeners'        => array('array'),
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
    public function buildStep(StepInterface $step, MapInterface $map, array $options = array())
    {
        return $step;
    }
}
