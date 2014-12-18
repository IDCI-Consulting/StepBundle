<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Map;

interface MapNavigationInterface
{
    /**
     * Navigate to a step.
     *
     * @param string $destination The identifier name of the destination step.
     * @param array  $data        The data for the previous step. Must be null for a simple GET request.
     *
     * @return IDCI\Bundle\StepBundle\Map\View\MapView The resulting view.
     */
    public function navigate($destination, array $data = null);
}
