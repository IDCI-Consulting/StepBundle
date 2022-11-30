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
        return [
            new TwigFunction(
                'step_stylesheets',
                [$this, 'stepStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'step_javascripts',
                [$this, 'stepJavascripts'],
                ['is_safe' => ['html', 'js']]
            ),
            new TwigFunction(
                'step_breadcrumb',
                [$this, 'stepBreadcrumb'],
                [
                    'is_safe' => ['html', 'js'],
                    'needs_environment' => true,
                ]
            ),
            new TwigFunction(
                'pre_step_content',
                [$this, 'preStepContent'],
                ['is_safe' => ['html', 'js']]
            ),
            new TwigFunction(
                'step',
                [$this, 'step'],
                [
                    'is_safe' => ['html', 'js'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    /**
     * Returns step stylesheets.
     */
    public function stepStylesheets(NavigatorInterface $navigator): string
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
     */
    public function stepJavascripts(NavigatorInterface $navigator): string
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
     */
    public function stepBreadcrumb(Environment $twig, Breadcrumb $breadcrumb): string
    {
        return $twig->render(
            '@IDCIStep/Step/breadcrumb.html.twig',
            [
                'breadcrumb' => $breadcrumb,
            ]
        );
    }

    /**
     * Returns the pre step content.
     */
    public function preStepContent(NavigatorInterface $navigator): string
    {
        return $navigator->getCurrentStep()->getPreStepContent();
    }

    /**
     * Returns step.
     */
    public function step(Environment $twig, NavigatorInterface $navigator, string $theme = null): string
    {
        return $twig->render(
            '@IDCIStep/Step/default.html.twig',
            [
                'navigator' => $navigator,
                'theme' => $theme,
            ]
        );
    }
}
