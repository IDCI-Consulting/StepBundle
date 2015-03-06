<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class FlowDataMergerStepEventAction extends AbstractStepEventAction
{
    /**
     * @var \Twig_Environment
     */
    protected $merger;

    /**
     * Constructor
     *
     * @param
     */
    public function __construct(\Twig_Environment $merger)
    {
        $this->merger = $merger;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $parameters = array()
    )
    {
        $step = $navigator->getCurrentStep();
        $configuration = $step->getConfiguration();

        if ($configuration['type'] instanceof \IDCI\Bundle\StepBundle\Step\Type\FormStepType) {
            $form = $form->get('_data');
        }

        foreach ($parameters['fields'] as $field => $toMerge) {
            $form
                ->get($field)
                ->setData($this->merger->render(
                    $toMerge,
                    array('flow_data' => $navigator->getFlow()->getData())
                ))
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array('fields' => array()))
            ->setAllowedTypes(array(
                'fields' => array('array')
            ))
        ;
    }
}