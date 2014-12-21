<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Twig;

class StepExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'idci_step_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('step_widget', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step_errors', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step_label', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step_row', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step_rest', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step', null, array('node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step_start', null, array('node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('step_end', null, array('node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => array('html'))),
        );
    }
}
