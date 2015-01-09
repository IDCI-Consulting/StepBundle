<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Serialization;

interface SerializerProviderInterface
{
    /**
     * Provide a new serializer.
     *
     * @return \JMS\Serializer\SerializerInterface The serializer.
     */
    public function get();
}