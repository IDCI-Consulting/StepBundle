<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Twig;

use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class StepExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('step_stylesheets', array($this, 'stepStylesheets')),
            new \Twig_SimpleFunction('step_javascripts', array($this, 'stepJavascripts')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_extension';
    }

    /**
     *
     */
    public function stepStylesheets(NavigatorInterface $navigator)
    {
        return 'css';
    }

    /**
     *
     */
    public function stepJavascripts(NavigatorInterface $navigator)
    {
        return 'js';
    }
}
