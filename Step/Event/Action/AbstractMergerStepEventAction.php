<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractMergerStepEventAction extends AbstractStepEventAction
{
    /**
     * @var \Twig_Environment
     */
    protected $merger;

    /**
     * Constructor
     *
     * @param \Twig_Environment $merger The merger.
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
                    $this->buildRenderParameters($navigator, $parameters)
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

    /**
     * Build render parameters
     *
     * @param NavigatorInterface $navigator  The navigator.
     * @param array              $parameters The parameters.
     *
     * @return array
     */
    abstract protected function buildRenderParameters(NavigatorInterface $navigator, $parameters = array());
}