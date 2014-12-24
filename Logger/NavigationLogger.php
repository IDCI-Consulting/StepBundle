<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Logger;

use Symfony\Component\DependencyInjection\ContainerInterface;

class NavigationLogger implements NavigationLoggerInterface
{
    protected $logger;
    protected $stopwatch;

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
}