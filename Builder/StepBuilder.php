<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Builder;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use IDCI\Bundle\StepBundle\Type\StepTypeInterface;
use IDCI\Bundle\StepBundle\Factory\StepFactoryInterface;
use IDCI\Bundle\StepBundle\Exception\UnexpectedTypeException;
use IDCI\Bundle\StepBundle\Step;

class StepBuilder implements \IteratorAggregate, StepBuilderInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var string
     */
    private $name;

    /**
     * @var StepTypeInterface
     */
    private $type;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var StepFactoryInterface
     */
    private $stepFactory;

    /**
     * @var array
     */
    private $options;

    /**
     * @var StepBuilderInterface[]
     */
    private $children = array();

    /**
     * Creates a new form builder.
     *
     * @param string                   $name
     * @param EventDispatcherInterface $dispatcher
     * @param StepFactoryInterface     $factory
     * @param array                    $options
     */
    public function __construct($name, EventDispatcherInterface $dispatcher, StepFactoryInterface $factory, array $options = array())
    {
        $this->name         = (string) $name;
        $this->dispatcher   = $dispatcher;
        $this->options      = $options;
        $this->setStepFactory($factory);
    }

    /**
     * {@inheritdoc}
     */
    public function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getStepFactory()
    {
        return $this->stepFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(StepTypeInterface $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setStepFactory(StepFactoryInterface $stepFactory)
    {
        $this->stepFactory = $stepFactory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function add($child, $type = null, array $options = array())
    {
        if ($child instanceof self) {
            $this->children[$child->getName()] = $child;

            return $this;
        }

        if (!is_string($child) && !is_int($child)) {
            throw new UnexpectedTypeException($child, 'string, integer or IDCI\Bundle\StepBundle\Builder\StepBuilder');
        }

        if (null !== $type && !is_string($type) && !$type instanceof StepTypeInterface) {
            throw new UnexpectedTypeException($type, 'string or IDCI\Bundle\StepBundle\Type\StepTypeInterface');
        }

        // Add to "children" to maintain order
        $this->children[$child] = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function create($name, $type = null, array $options = array())
    {
        if (null === $type) {
            $type = 'content';
        }

        return $this->getStepFactory()->createNamedBuilder($name, $type, null, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (isset($this->children[$name])) {
            return $this->children[$name];
        }

        throw new InvalidArgumentException(sprintf(
            'The child with the name "%s" does not exist.',
            $name
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        unset($this->children[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        if (isset($this->children[$name])) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * {@inheritdoc}
     */
    public function getStep()
    {
        $step = new Step();

        foreach ($this->children as $child) {
            // Automatic initialization is only supported on root forms
            $form->add($child->getStep());
        }

        return $form;
    }
}