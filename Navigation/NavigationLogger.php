<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class NavigationLogger implements NavigationLoggerInterface
{
    /**
     * @var Symfony\Component\HttpKernel\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     *@var IDCI\Bundle\StepBundle\Navigation\NavigatorInterface
     */
    protected $navigator;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->navigator = null;
        $this->logger    = null;
        $this->stopwatch = null;

        if ($container->has('logger')) {
            $this->logger = $container->get('logger');
        }

        if ($container->has('debug.stopwatch')) {
            $this->stopwatch = $container->get('debug.stopwatch');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function startNavigation()
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->start('idci_step.navigation');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopNavigation(NavigatorInterface $navigator)
    {
        $this->navigator = $navigator;

        if (null !== $this->stopwatch) {
            $this->stopwatch->stop('idci_step.navigation');
        }

        if (null !== $this->logger) {
            $this->logger->info(sprintf('Step navigation [%s]',
                $this->navigator->getMap()->getName()),
                array(
                )
            );
            $this->logger->debug(sprintf('Step navigation [%s] debug',
                $this->navigator->getMap()->getName()),
                array(
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasNavigation()
    {
        return null !== $this->navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function getNavigation()
    {
        if (!$this->hasNavigation()) {
            return array();
        }

        return array(
            'map'          => $this->navigator->getMap(),
            'current_step' => $this->navigator->getCurrentStep(),
            'flow'         => $this->navigator->getFlow()
        );
    }
}