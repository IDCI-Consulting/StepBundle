<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataCollector;

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
        $this->data['navigation'] = $this->logger->getNavigation();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'navigation';
    }

    /**
     * Get navigation
     *
     * @return array
     */
    public function getNavigation()
    {
        return $this->data['navigation'];
    }
}
