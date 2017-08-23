<?php

/**
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\StepBundle\AssetProvider;

use IDCI\Bundle\AssetLoaderBundle\AssetProvider\AssetProviderInterface;
use IDCI\Bundle\AssetLoaderBundle\Model\Asset;
use IDCI\Bundle\AssetLoaderBundle\Model\AssetCollection;

class MapAssetProvider implements AssetProviderInterface
{
    /**
     * @var AssetCollection
     */
    private $collection;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->collection = new AssetCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getAssetCollection()
    {
        return $this->collection;
    }

    /**
     * register the assets
     */
    public function registerAssets()
    {
        $this->collection->add(new Asset('IDCIStepBundle:Map:map_diagram_assets.html.twig'));
    }
}
