<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Path\PathInterface;

class MapStepType extends AbstractType
{
    /**
     * The map.
     *
     * @var MapInterface
     */
    protected $map;

    /**
     * The step name.
     *
     * @var string
     */
    protected $stepName;

    /**
     * Constructor
     *
     * @param MapInterface $map      The map.
     * @param string       $stepName The step name.
     */
    public function __construct(MapInterface $map, $stepName)
    {
        $this->map = $map;
        $this->stepName = $stepName;
    }

    /**
     * Get map
     *
     * @return MapInterface
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Get step
     *
     * @return StepInterface
     */
    public function getStep()
    {
        return $this->getMap()->getStep($this->stepName);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO
        $this->buildSubmit($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildSubmit(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->getMap()->getPaths($this->stepName) as $i => $path) {
            $builder->add($i, 'submit', array(
                'label' => $path->getLabel()
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return sprintf('idci_step_%s_%s',
            $this->getMap()->getName(),
            $this->getStep()->getName()
        );
    }
}