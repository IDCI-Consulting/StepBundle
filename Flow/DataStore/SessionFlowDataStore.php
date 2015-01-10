<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;
use IDCI\Bundle\StepBundle\Flow\Flow;

class SessionFlowDataStore implements FlowDataStoreInterface
{
    /**
     * {@inheritdoc}
     */
    public function set($mapFingerPrint, Request $request, Flow $flow)
    {
    
    }

    /**
     * {@inheritdoc}
     */
    public function get($mapFingerPrint, Request $request)
    {
    
    }

    /**
     * {@inheritdoc}
     */
    public function clear($mapFingerPrint, Request $request)
    {
    
    }
}
