<?php

namespace IDCI\Bundle\StepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Test controller.
 *
 * @Route("/step")
 */
class TestController extends Controller
{
    /**
     * Test.
     *
     * @Route("/", name="idci_step")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $flow = $this
            ->get('idci_step.factory')
            ->createBuilder('flow')
            ->add('intro', 'content', array())
        ;

        var_dump($flow->getStep());die;
    }
}