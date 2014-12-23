<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NavigatorType extends AbstractType
{
    /**
     * The navigator.
     *
     * @var NavigatorInterface
     */
    protected $navigator;

    /**
     * Constructor
     *
     * @param NavigatorInterface $navigator The navigator.
     */
    public function __construct(NavigatorInterface $navigator)
    {
        $this->navigator = $navigator;
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
        foreach ($this->navigator->getAvailablePaths() as $i => $path) {
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
        return sprintf('idci_step_%s', $this->navigator->getPositionName());
    }
}