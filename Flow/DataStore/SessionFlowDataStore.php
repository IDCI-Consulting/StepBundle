<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\Flow\DataStore;

use Symfony\Component\HttpFoundation\Request;

class SessionFlowDataStore extends AbstractFlowDataStore
{
    /**
     * {@inheritdoc}
     */
    public function store(Request $request, $id, $data)
    {
        $request->getSession()->set($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve(Request $request, $id)
    {
        if (!$request->getSession()->has($id)) {
            return null;
        }

        return $request->getSession()->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function doClear(Request $request, $id)
    {
        $request->getSession()->remove($id);
    }
}
