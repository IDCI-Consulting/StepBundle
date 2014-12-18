<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionDataStore implements FlowDataStoreInterface
{
    /**
     * The session.
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param SessionInterface $session The session.
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function save($mapName, $flowName, $value)
    {
        $this->session->set($this->formatFlowName($mapName, $flowName), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($mapName, $flowName)
    {
        return $this->session->get($this->formatFlowName($mapName, $flowName), null);
    }

    /**
     * Format the saved identifier name of the flow.
     *
     * @param string $mapName  The identifier name of the map.
     * @param string $flowName The identifier name of the flow.
     */
    protected function formatFlowName($mapName, $flowName)
    {
        return sprintf('idci_step.flow_%s_%s', $mapName, $flowName);
    }
}
