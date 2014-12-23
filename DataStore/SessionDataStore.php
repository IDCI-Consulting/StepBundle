<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use JMS\Serializer\SerializerInterface;

class SessionDataStore extends AbstractSerializerDataStore
{
    /**
     * The session.
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param SerializerInterface $serializer The serializer.
     * @param SessionInterface    $session    The session.
     */
    public function __construct(
        SerializerInterface $serializer,
        SessionInterface $session
    )
    {
        $this->session = $session;

        parent::__construct($serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function set($namespace, $key, $data = null)
    {
        $namespaceData = $this->get($namespace);

        if ($data) {
            $arrayData = json_decode($this->serialize($data), true);

            $namespaceData[$key] = $arrayData;
        } else {
            unset($namespaceData[$key]);
        }

        $this->session->set($this->formatName($namespace), json_encode($namespaceData));
    }

    /**
     * {@inheritdoc}
     */
    public function get($namespace, $key = null)
    {
        $namespaceData = json_decode(
            $this->session->get($this->formatName($namespace), '{}'),
            true
        );

        return null === $key
            ? $namespaceData
            : (isset($namespaceData[$key])
                ? (is_object($namespaceData[$key])
                    ? $this->deserialize(json_encode($namespaceData[$key]))
                    : $namespaceData[$key]
                )
                : null
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function clear($namespace, $key = null)
    {
        $this->session->remove($this->formatName($namespace));
    }

    /**
     * Format the saved identifier name of the namespace.
     *
     * @param string $namespace The namespace.
     */
    protected function formatName($namespace)
    {
        return sprintf('idci_step.flow.%s', $namespace);
    }
}
