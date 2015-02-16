<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Event\Action;

use Symfony\Component\Form\FormInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class TestPathEventAction implements PathEventActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(FormInterface $form, NavigatorInterface $navigator, $parameters = array())
    {
        die('test');
    }
}