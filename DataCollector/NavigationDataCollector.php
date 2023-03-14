<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataCollector;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigationLoggerInterface;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NavigationDataCollector extends AbstractDataCollector
{
    /**
     * @var NavigationLoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     */
    public function __construct(NavigationLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $this->data['idci_step.navigation'] = $this->logger->getNavigation();
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'idci_step.navigation';
    }

    /**
     * Has navigation.
     */
    public function hasNavigation(): bool
    {
        return null !== $this->data['idci_step.navigation'];
    }

    /**
     * Get map.
     */
    public function getMap(): ?MapInterface
    {
        if (!$this->hasNavigation()) {
            return null;
        }

        return $this->data['idci_step.navigation']['map'];
    }

    /**
     * Get flow.
     */
    public function getFlow(): ?FlowInterface
    {
        if (!$this->hasNavigation()) {
            return null;
        }

        return $this->data['idci_step.navigation']['flow'];
    }
}
