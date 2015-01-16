ConfigurationFetcher
====================


ConfigurationFetcher are services that fetch a configuration raw and provide an
understandable data structure that is use by a MapConfigurationBuilder to build a MapBuilder.
A ConfigurationFetcher is identitfy by a name.
By default this bundle provide some configuration fetcher.


## Default configuration fetcher

This is the default ConfigurationFetcher, the raw is located in your configuration.
To define a configuration :
```yml
idci_step:
    configurations:
        participation_map:
            # Thomas demo configuration
```
In this example, "participation_map" is the ConfigurationFetcher identifier.


## Create your own configuration fetcher

If you wish to create your own configuration fetcher, you have to create a class
which extends `AbstractConfigurationFetcher` and implement necessary methods.
```php
<?php
// src/My/Bundle/Configuration/MapFetcher/MyConfigurationFetcher.php

namespace My\Bundle\Configuration\MapFetcher;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Configuration\Fetcher\AbstractConfigurationFetcher;
use IDCI\Bundle\StepBundle\Exception\FetchConfigurationException;

class MyConfigurationFetcher extends AbstractConfigurationFetcher
{
    /**
     * {@inheritDoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        ...
    }


    /**
     * {@inheritDoc}
     */
    public function doFetch(array $parameters = array())
    {
        ...
    }
}
```

Then declare this new ConfigurationFetcher as service:
```yml
services:
    idci_step.configuration.fetcher.my_fetcher:
        class: My\Bundle\Configuration\MapFetcher\MyConfigurationFetcher
        arguments: []
        tags:
            - { name: idci_step.configuration.fetcher, alias: my_fetcher }
```
The alias "my_fetcher" will be the ConfigurationFetcher identifier.

The doFetch function must return an array that should be in the following format:
```
array(
    // Thomas demo data
)
```

To check if ConfigurationFetcher are well configurated, you could list all them:
```sh
$ php app/console container:debug | grep "idci_step\.configuration.fetcher\."
```
