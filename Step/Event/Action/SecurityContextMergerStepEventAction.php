<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Step\Event\Action;

use Symfony\Component\Security\Core\SecurityContextInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class SecurityContextMergerStepEventAction extends AbstractMergerStepEventAction
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Constructor
     *
     * @param \Twig_Environment        $merger          The merger.
     * @param SecurityContextInterface $securityContext The security context.
     */
    public function __construct(\Twig_Environment $merger, SecurityContextInterface $securityContext)
    {
        parent::__construct($merger);

        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildRenderParameters(NavigatorInterface $navigator, $parameters = array())
    {
        $user = null;

        if (null !== $this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
        }

        return array('user' => $user);
    }
}