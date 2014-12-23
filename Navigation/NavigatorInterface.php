<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Navigation;

interface NavigatorInterface
{
    /**
     * Get the map
     *
     * @return MapInterface
     */
    public function getMap();

    /**
     * Get the flow
     *
     * @return FlowInterface
     */
    public function getFlow();

    /**
     * Get the data store
     *
     * @return DataStoreInterface
     * /
    public function getDataStore();

    /**
     * Get the HTTP request
     *
     * @return Symfony\Component\HttpFoundation\Request
     * /
    public function getRequest();


    // TODO: add createStepView
}