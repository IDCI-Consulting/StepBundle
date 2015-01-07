<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

class FlowEventNotifier implements FlowEventNotifierInterface
{
    /**
     * The list of listeners indexed by identifier alias.
     *
     * @var array
     */
    protected $listeners;

    /**
     * Add a listener.
     *
     * @param string                $alias    The identifier alias of the listener.
     * @param FlowListenerInterface $listener The listener.
     */
    public function addListener($alias, FlowListenerInterface $listener)
    {
        $this->listeners[$alias] = $listener;
    }

    /**
     * {@inheritdoc}
     */
    protected function getListener($alias)
    {
        if (!isset($this->listeners)) {
            throw new \InvalidArgumentException(sprintf(
                'No listener "%s" defined.',
                $alias
            ));
        }

        return $this->listeners[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function notifyOne($alias, FlowInterface $flow, array $data = array())
    {
        $this->getListener($alias)->handleFlow($flow, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function notify(array $listeners, FlowInterface $flow)
    {
        foreach ($listeners as $listener) {
            $alias = $listener['alias'];
            unset($listener['alias']);
            $data = $listener;

            $this->notifyOne($alias, $flow, $data);
        }
    }
}
