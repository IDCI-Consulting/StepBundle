<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

use IDCI\Bundle\StepBundle\Flow\FlowRecorderInterface;
use IDCI\Bundle\StepBundle\Path\PathBuilderInterface;
use IDCI\Bundle\StepBundle\Step\StepBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class MapBuilderFactory implements MapBuilderFactoryInterface
{
    /**
     * @var FlowRecorderInterface
     */
    private $flowRecorder;

    /**
     * @var StepBuilderInterface
     */
    private $stepBuilder;

    /**
     * @var PathBuilderInterface
     */
    private $pathBuilder;

    /**
     * @var Environment
     */
    private $merger;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor.
     *
     * @param FlowRecorderInterface $flowRecorder the flow recorder
     * @param StepBuilderInterface  $stepBuilder  the step builder
     * @param PathBuilderInterface  $pathBuilder  the path builder
     * @param Environment           $merger       the twig merger
     * @param TokenStorageInterface $tokenStorage the security context
     * @param SessionInterface      $session      the session
     */
    public function __construct(
        FlowRecorderInterface $flowRecorder,
        StepBuilderInterface  $stepBuilder,
        PathBuilderInterface  $pathBuilder,
        Environment           $merger,
        TokenStorageInterface $tokenStorage,
        SessionInterface      $session
    ) {
        $this->flowRecorder = $flowRecorder;
        $this->stepBuilder = $stepBuilder;
        $this->pathBuilder = $pathBuilder;
        $this->merger = $merger;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
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
            $this->flowRecorder,
            $this->stepBuilder,
            $this->pathBuilder,
            $this->merger,
            $this->tokenStorage,
            $this->session,
            $name,
            $data,
            $options
        );
    }
}
