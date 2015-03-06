<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class FlowDataMergerStepEventAction extends AbstractMergerStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function buildRenderParameters(NavigatorInterface $navigator, $parameters = array())
    {
        return array('flow_data' => $navigator->getFlow()->getData());
    }
}