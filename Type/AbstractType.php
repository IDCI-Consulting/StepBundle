<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */
 
namespace IDCI\Bundle\StepBundle\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\EventDispatcher\EventDispatcher;
use IDCI\Bundle\StepBundle\Builder\StepBuilderInterface;
use IDCI\Bundle\StepBundle\Factory\StepFactoryInterface;
use IDCI\Bundle\StepBundle\Builder\StepBuilder;
use IDCI\Bundle\StepBundle\View\StepView;
use IDCI\Bundle\StepBundle\StepInterface;

abstract class AbstractType implements StepTypeInterface
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * {@inheritdoc}
     */
    public function buildStep(StepBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(StepView $view, StepInterface $form, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createBuilder(StepFactoryInterface $factory, $name, array $options = array())
    {
        $options = $this->getOptionsResolver()->resolve($options);

        $builder = new StepBuilder($name, new EventDispatcher(), $factory, $options);
        $builder->setType($this);

        $this->buildStep($builder, $options);

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionsResolver()
    {
        if (null === $this->optionsResolver) {
            $this->optionsResolver = new OptionsResolver();
            $this->setDefaultOptions($this->optionsResolver);
        }

        return $this->optionsResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}
