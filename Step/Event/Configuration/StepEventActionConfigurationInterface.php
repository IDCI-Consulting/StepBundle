<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Configuration;

interface StepEventActionConfigurationInterface
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the parent.
     *
     * @return StepEventActionConfigurationInterface
     */
    public function getParent();

    /**
     * Returns the description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Is abstract.
     *
     * @return bool
     */
    public function isAbstract();

    /**
     * Returns extra form options.
     *
     * @return array
     */
    public function getExtraFormOptions();
}
