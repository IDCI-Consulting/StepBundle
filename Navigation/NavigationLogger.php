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
    protected $logger;
    protected $stopwatch;
    protected $navigator;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = null;
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
    public function startInit()
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->start('idci_step_init');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopInit(NavigatorInterface $navigator)
    {
        $this->navigator = $navigator;

        if (null !== $this->stopwatch) {
            $this->stopwatch->stop('idci_step_init');
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
    public function getNavigation()
    {
        return array(
            'map'         => $this->navigator->getMap(),
            'currentStep' => $this->navigator->getCurrentStep(),
            'flow'        => $this->navigator->getFlow(),
        );
    }
}