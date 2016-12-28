<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Brahim BOUKOUFALLAH <brahim.boukoufallah@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataCollector;

use IDCI\Bundle\StepBundle\Flow\FlowInterface;
use IDCI\Bundle\StepBundle\Map\MapInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use IDCI\Bundle\StepBundle\Navigation\NavigationLoggerInterface;

class NavigationDataCollector extends DataCollector
{
    /**
     * @var NavigationLoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param NavigationLoggerInterface $logger
     */
    public function __construct(NavigationLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['idci_step.navigation'] = $this->logger->getNavigation();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step.navigation';
    }

    /**
     * Has navigation
     *
     * @return boolean
     */
    public function hasNavigation()
    {
        return null !== $this->data['idci_step.navigation'];
    }

    /**
     * Get map
     *
     * @return MapInterface|null
     */
    public function getMap()
    {
        if (!$this->hasNavigation()) {
            return null;
        }

        return $this->data['idci_step.navigation']['map'];
    }

    /**
     * Get flow
     *
     * @return FlowInterface|null
     */
    public function getFlow()
    {
        if (!$this->hasNavigation()) {
            return null;
        }

        return $this->data['idci_step.navigation']['flow'];
    }
}
