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
            new \Twig_SimpleFunction(
                'step_stylesheets',
                array($this, 'stepStylesheets'),
                array('is_safe' => array('html'))
            ),
            new \Twig_SimpleFunction(
                'step_javascripts',
                array($this, 'stepJavascripts'),
                array('is_safe' => array('html', 'js'))
            ),
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
     * Returns step stylesheets
     *
     * @param NavigatorInterface $navigator
     *
     * @return string
     */
    public function stepStylesheets(NavigatorInterface $navigator)
    {
        $configuration = $navigator->getCurrentStep()->getConfiguration();

        if (null !== $configuration['options']['css']) {
            return sprintf(
                '<style type="text/css">%s</style>',
                $configuration['options']['css']
            );
        }
    }

    /**
     * Returns step javascripts
     *
     * @param NavigatorInterface $navigator
     *
     * @return string
     */
    public function stepJavascripts(NavigatorInterface $navigator)
    {
        $configuration = $navigator->getCurrentStep()->getConfiguration();

        if (null !== $configuration['options']['js']) {
            return sprintf(
                '<script type="text/javascript">%s</script>',
                $configuration['options']['js']
            );
        }
    }
}
