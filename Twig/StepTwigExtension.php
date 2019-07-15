<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Twig;

use IDCI\Bundle\StepBundle\Breadcrumb\Breadcrumb;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class StepTwigExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction(
                'step_breadcrumb',
                array($this, 'stepBreadcrumb'),
                array(
                    'is_safe' => array('html', 'js'),
                    'needs_environment' => true,
                )
            ),
            new \Twig_SimpleFunction(
                'pre_step_content',
                array($this, 'preStepContent'),
                array('is_safe' => array('html', 'js'))
            ),
            new \Twig_SimpleFunction(
                'step',
                array($this, 'step'),
                array(
                    'is_safe' => array('html', 'js'),
                    'needs_environment' => true,
                )
            ),
            new \Twig_SimpleFunction(
                'draw_map',
                array($this, 'drawMap'),
                array(
                    'is_safe' => array('html', 'js'),
                    'needs_environment' => true,
                )
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_twig_extension';
    }

    /**
     * Returns step stylesheets.
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
     * Returns step javascripts.
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

    /**
     * Returns step breadcrumb.
     *
     * @param Breadcrumb $breadcrumb
     *
     * @return string
     */
    public function stepBreadcrumb(\Twig_Environment $twig, Breadcrumb $breadcrumb)
    {
        return $twig->render(
            '@IDCIStep/Step/breadcrumb.html.twig',
            array(
                'breadcrumb' => $breadcrumb,
            )
        );
    }

    /**
     * Returns the pre step content.
     *
     * @param NavigatorInterface $navigator
     *
     * @return string
     */
    public function preStepContent(NavigatorInterface $navigator)
    {
        return $navigator->getCurrentStep()->getPreStepContent();
    }

    /**
     * Returns step.
     *
     * @param \Twig_Environment  $twig
     * @param NavigatorInterface $navigator
     * @param string             $theme
     *
     * @return string
     */
    public function step(\Twig_Environment $twig, NavigatorInterface $navigator, $theme = null)
    {
        return $twig->render(
            '@IDCIStep/Step/default.html.twig',
            array(
                'navigator' => $navigator,
                'theme' => $theme,
            )
        );
    }

    /**
     * Render the map drawing template.
     *
     * @param \Twig_Environment $twig
     * @param string            $jsonMap
     * @param string            $id
     *
     * @return string
     */
    public function drawMap(\Twig_Environment $twig, $jsonMap, $id)
    {
        return $twig->render(
            '@IDCIStep/Map/map_diagram.html.twig',
            array(
                'jsonMap' => $jsonMap,
                'id' => $id,
            )
        );
    }
}
