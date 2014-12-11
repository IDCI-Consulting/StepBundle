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
        $map = $this
            ->get('idci_step.map.factory')
            ->createBuilder()
            ->addStep("intro", "content", array(
                "content" => "<h1>My content</h1>",
            ))
        ;

        var_dump($map);die;
    }
}