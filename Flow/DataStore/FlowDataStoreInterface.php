<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Flow\Flow;

interface FlowDataStoreInterface
{
    /**
     * Set a flow.
     *
     * @param string  $mapFingerPrint   The map finger print.
     * @param Request $request          The HTTP request.
     * @param Flow    $flow             The flow.
     */
    public function set($mapFingerPrint, Request $request, Flow $flow);

    /**
     * Get a flow.
     *
     * @param string  $mapFingerPrint   The map finger print.
     * @param Request $request          The HTTP request.
     *
     * @return Flow|null The flow or null if there is no corresponding flow.
     */
    public function get($mapFingerPrint, Request $request);

    /**
     * Clear flow.
     *
     * @param string  $mapFingerPrint   The map finger print.
     * @param Request $request The HTTP request.
     */
    public function clear($mapFingerPrint, Request $request);
}
