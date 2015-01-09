<?php

/**
 * @author:  Thomas Prelot <tprelot@gmail.com>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\DataStore;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IDCI\Bundle\StepBundle\Serialization\SerializerProviderInterface;

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
     * @param SerializerProviderInterface $serializerProvider The serializer provider.
     * @param SessionInterface            $session            The session.
     */
    public function __construct(
        SerializerProviderInterface $serializerProvider,
        SessionInterface $session
    )
    {
        $this->session = $session;

        parent::__construct($serializerProvider);
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

        $this->session->set(
            $this->formatName($namespace),
            json_encode($namespaceData)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get($namespace, $key = null)
    {
        $namespacedData = json_decode(
            $this->session->get($this->formatName($namespace), '{}'),
            true
        );

        if (null === $key) {
            return $namespacedData;
        }

        if (!isset($namespacedData[$key])) {
            return null;
        }

        return $this->deserialize(json_encode($namespacedData[$key]));
    }

    /**
     * {@inheritdoc}
     */
    public function clear($namespace)
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
        return sprintf('idci_step.navigation.%s', $namespace);
    }
}
