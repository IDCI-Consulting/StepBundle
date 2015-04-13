<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Path\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;

class ConditionalDestinationPathType extends AbstractPathType
{
    /**
     * @var \Twig_Environment
     */
    private $merger;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * Constructor
     *
     * @param Twig_Environment         $merger          The twig merger.
     * @param SecurityContextInterface $securityContext The security context.
     * @param SessionInterface         $session         The session.
     */
    public function __construct(
        \Twig_Environment        $merger,
        SecurityContextInterface $securityContext,
        SessionInterface         $session
    )
    {
        $this->merger          = $merger;
        $this->securityContext = $securityContext;
        $this->session         = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setRequired(array('source', 'destinations', 'default_destination'))
            ->setAllowedTypes(array(
                'source'       => 'string',
                'destinations' => 'array',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPath(array $steps, array $options = array())
    {
        $path = parent::buildPath($steps, $options);
        $path->setSource($steps[$options['source']]);

        foreach ($options['destinations'] as $name => $rule) {
            $path->addDestination($steps[$name]);
        }
        $path->addDestination($steps[$options['default_destination']]);

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveDestination(array $options, NavigatorInterface $navigator)
    {
        $user = null;
        if (null !== $this->securityContext->getToken()) {
            $user = $this->securityContext->getToken()->getUser();
        }

        foreach ($options['destinations'] as $name => $rule) {
            $merged = $this->merger->render($rule, array(
                'user'      => $user,
                'session'   => $this->session,
                'flow_data' => $navigator->getFlow()->getData(),
            ));

            if ($merged === '1') {
                return $name;
            }
        }

        return $options['default_destination'];
    }
}