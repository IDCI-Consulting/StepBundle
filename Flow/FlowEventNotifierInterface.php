<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow;

interface FlowEventNotifierInterface
{
    /**
     * Notify a listener.
     *
     * @param string        $alias The alias of the listener.
     * @param FlowInterface $flow  The flow.
     * @param array         $data  The associated data.
     */
    public function notifyOne($alias, FlowInterface $flow, array $data = array());

    /**
     * Notify a list of listeners.
     *
     *   The listeners must be of the format:
     *   array(
     *     array('alias' => 'name1')
     *     array('alias' => 'name2', 'option1' => 1, 'option2' => '4')
     *     array('alias' => 'name3', 'option3' => 5)
     *     // ...
     *   )
     *
     * @param string        $alias The alias of the listener.
     * @param FlowInterface $flow  The flow.
     */
    public function notify(array $listeners, FlowInterface $flow);
}
