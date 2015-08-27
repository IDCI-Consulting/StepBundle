<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IDCI\Bundle\StepBundle\Step\StepBuilderInterface;
use IDCI\Bundle\StepBundle\Path\PathBuilderInterface;
use IDCI\Bundle\StepBundle\Map\MapNavigatorInterface;

class MapBuilderFactory implements MapBuilderFactoryInterface
{
    /**
     * @var StepBuilderInterface
     */
    private $stepBuilder;

    /**
     * @var PathBuilderInterface
     */
    private $pathBuilder;

    /**
     * @var \Twig_Environment
     */
    private $merger;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor
     *
     * @param StepBuilderInterface     $stepBuilder     The step builder.
     * @param PathBuilderInterface     $pathBuilder     The path builder.
     * @param Twig_Environment         $merger          The twig merger.
     * @param SecurityContextInterface $securityContext The security context.
     * @param SessionInterface         $session         The session.
     */
    public function __construct(
        StepBuilderInterface     $stepBuilder,
        PathBuilderInterface     $pathBuilder,
        \Twig_Environment        $merger,
        SecurityContextInterface $securityContext,
        SessionInterface         $session
    )
    {
        $this->stepBuilder     = $stepBuilder;
        $this->pathBuilder     = $pathBuilder;
        $this->merger          = $merger;
        $this->securityContext = $securityContext;
        $this->session         = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function createBuilder(array $data = array(), array $options = array())
    {
        return $this->createNamedBuilder('map', $data, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function createNamedBuilder($name = null, array $data = array(), array $options = array())
    {
        return new MapBuilder(
            $name,
            $data,
            $options,
            $this->stepBuilder,
            $this->pathBuilder,
            $this->merger,
            $this->securityContext,
            $this->session
        );
    }
}