<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\SecurityContextInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

abstract class AbstractPathEventAction implements PathEventActionInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $merger;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Constructor
     *
     * @param \Twig_Environment        $merger          The merger.
     * @param SecurityContextInterface $securityContext The security context.
     */
    public function __construct(\Twig_Environment $merger, SecurityContextInterface $securityContext)
    {
        $this->merger          = $merger;
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $pathIndex,
        array $parameters = array()
    )
    {
        $resolver = new OptionsResolver();
        $this->setDefaultParameters($resolver);

        return $this->doExecute(
            $form,
            $navigator,
            $pathIndex,
            $resolver->resolve($parameters)
        );
    }

    /**
     * Set default parameters.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
    }

    /**
     * Do execute action.
     *
     * @param FormInterface      $form       The form.
     * @param NavigatorInterface $navigator  The navigator.
     * @param integer            $pathIndex  The path index.
     * @param array              $parameters The resolved parameters.
     */
    abstract protected function doExecute(
        FormInterface $form,
        NavigatorInterface $navigator,
        $pathIndex,
        $parameters = array()
    );
}