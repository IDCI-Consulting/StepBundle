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
            new \Twig_SimpleFunction(
                'pre_step_content',
                array($this, 'preStepContent'),
                array('is_safe' => array('html', 'js'))
            ),
            new \Twig_SimpleFunction(
                'step',
                array($this, 'step'),
                array(
                    'is_safe'           => array('html', 'js'),
                    'needs_environment' => true,
                )
            ),
            new \Twig_SimpleFunction(
                'draw_step',
                array($this, 'draw_step'),
                array(
                    'is_safe'           => array('html', 'js'),
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

    /**
     * Returns the pre step content
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
     * Returns step
     *
     * @param Twig_Environment   $twig
     * @param NavigatorInterface $navigator
     * @param string             $theme
     *
     * @return string
     */
    public function step(\Twig_Environment $twig, NavigatorInterface $navigator, $theme = null)
    {
        return $twig->render(
            'IDCIStepBundle:Step:default.html.twig',
            array(
                'navigator' => $navigator,
                'theme'     => $theme,
            )
        );
    }
    
    /**
     * Returns drawing
     *
     * @param Twig_Environment   $twig
     * @param NavigatorInterface $navigator
     * @param string             $theme
     *
     * @return string
     */
    public function draw_step(\Twig_Environment $twig, $json, $theme = null)
    {
        return $twig->render(
            'IDCIStepBundle:Step:step_drawing.html.twig',
            array(
                'json' => $json,
                'theme'     => $theme,
            )
        );
    }
}
