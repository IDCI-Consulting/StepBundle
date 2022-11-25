<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Twig;

use IDCI\Bundle\StepBundle\Breadcrumb\Breadcrumb;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StepTwigExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'step_stylesheets',
                array($this, 'stepStylesheets'),
                array('is_safe' => array('html'))
            ),
            new TwigFunction(
                'step_javascripts',
                array($this, 'stepJavascripts'),
                array('is_safe' => array('html', 'js'))
            ),
            new TwigFunction(
                'step_breadcrumb',
                array($this, 'stepBreadcrumb'),
                array(
                    'is_safe' => array('html', 'js'),
                    'needs_environment' => true,
                )
            ),
            new TwigFunction(
                'pre_step_content',
                array($this, 'preStepContent'),
                array('is_safe' => array('html', 'js'))
            ),
            new TwigFunction(
                'step',
                array($this, 'step'),
                array(
                    'is_safe' => array('html', 'js'),
                    'needs_environment' => true,
                )
            ),
        );
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
    public function stepBreadcrumb(Environment $twig, Breadcrumb $breadcrumb)
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
     * @param Environment        $twig
     * @param NavigatorInterface $navigator
     * @param string             $theme
     *
     * @return string
     */
    public function step(Environment $twig, NavigatorInterface $navigator, $theme = null)
    {
        return $twig->render(
            '@IDCIStep/Step/default.html.twig',
            array(
                'navigator' => $navigator,
                'theme' => $theme,
            )
        );
    }
}
