<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $start;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = null;
        $this->stopwatch = null;
        $this->data = null;
        $this->start = null;

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
        $this->start = microtime(true);

        $this->data = [
            'navigator' => null,
            'executionMS' => 0,
        ];

        if (null !== $this->stopwatch) {
            $this->stopwatch->start('idci_step.navigation');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stopNavigation(NavigatorInterface $navigator)
    {
        if (null !== $this->stopwatch) {
            $this->stopwatch->stop('idci_step.navigation');
        }

        $this->data = [
            'navigator' => $navigator,
            'executionMS' => (microtime(true) - $this->start) * 1000,
        ];

        if (null !== $this->logger) {
            $this->logger->info(
                sprintf(
                    'Step navigation [%s - %s]',
                    $navigator->getMap()->getName(),
                    $navigator->getMap()->getFootprint()
                ),
                []
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasNavigator(): bool
    {
        return isset($this->data['navigator']) && null !== $this->data['navigator'];
    }

    /**
     * {@inheritdoc}
     */
    public function getNavigation(): ?array
    {
        if (!$this->hasNavigator()) {
            return null;
        }

        return [
            'map' => $this->data['navigator']->getMap(),
            'flow' => $this->data['navigator']->getFlow(),
        ];
    }
}
