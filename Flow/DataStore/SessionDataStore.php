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
    public function save($name, $value)
    {
        $this->session->set($this->formatFlowName($name), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($name)
    {
        return $this->session->get($this->formatFlowName($name), null);
    }

    /**
     * Format the saved identifier name of the flow.
     *
     * @param string $name The identifier name of the flow.
     */
    protected function formatFlowName($name)
    {
        return sprintf('idci_step.flow_%s', $name);
    }
}
