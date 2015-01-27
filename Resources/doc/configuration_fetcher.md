Configuration Fetcher
=====================

ConfigurationFetcher are services that fetch a configuration raw and provide an
understandable data structure that is use by a MapConfigurationBuilder to build a MapBuilder.
A ConfigurationFetcher is identitfy by a name.
By default this bundle provide some configuration fetcher.


## Default configuration fetcher

This is the default ConfigurationFetcher, the raw is located in your configuration.
To define a configuration:

```yml
# app/config/config.yml

idci_step:
    maps:
        participation_map:
            name: participation map
            data:
                foo: bar
            steps:
                intro:
                    type: html
                    options:
                        title: Introduction
                        description: The first step
                        content: <h1>My content</h1>
                personal:
                    type: form
                    options:
                        title: Personal information
                        description: The personal data step
                        previous_options:
                            label: Back to first step
                        @builder:
                            worker: form_builder
                            parameters:
                                fields:
                                    -
                                        name: first_name
                                        type: text
                                    -
                                        name: last_name
                                        type: text
                purchase:
                    type: form
                    options:
                        title: Purchase information
                        description: The purchase data step
                        @builder:
                            worker: form_builder
                            parameters:
                                fields:
                                    -
                                        name: item
                                        type: text
                                    -
                                        name: purchase_date
                                        type: datetime
                fork1:
                    type: form
                    options:
                        title: Fork1 information
                        description: The fork1 data step
                        @builder:
                            worker: form_builder
                            parameters:
                                fields:
                                    -
                                        name: fork1_data
                                        type: textarea
                fork2:
                    type: form
                    options:
                        title: Fork2 information
                        description: The fork2 data step
                        @builder:
                            worker: form_builder
                            parameters:
                                fields:
                                    -
                                        name: fork2_data
                                        type: textarea
                end:
                    type: html
                    options:
                        title: The end
                        description: The last data step
                        content: <h1>The end</h1>
            paths:
                -
                    type: single
                    options:
                        source: intro
                        destination: personal
                        next_options:
                            label: next
                -
                    type: conditional_destination
                    options:
                        source: personal
                        destinations:
                            purchase:
                                rules: {}
                            fork2:
                                rules: {}
                -
                    type: single
                    options:
                        source: purchase
                        destination: fork1
                -
                    type: single
                    options:
                        source: purchase
                        destination: fork2
                        next_options:
                            label: next p

                -
                    type: single
                    options:
                        source: fork1
                        destination: end
                        next_options:
                            label: next f

                -
                    type: single
                    options:
                        source: fork2
                        destination: end
                        next_options:
                            label: last
                -
                    type: end
                    options:
                        source: end
                        next_options:
                            label: end
```

In this example, "participation_map" is the configuration fetcher identifier.

> The prefix `@` means you use a [worker](./configuration_worker.md) to inject an object in the options of the map, a step or a path.


## Create your own configuration fetcher

If you wish to create your own configuration fetcher, you have to create a class
which extends `AbstractConfigurationFetcher` and implement necessary methods.

```php
// src/My/OwnBundle/Configuration/MapFetcher/MyConfigurationFetcher.php

namespace My\OwnBundle\Configuration\MapFetcher;

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

Then declare this new configuration fetcher as service:

```yml
services:
    my_own.step_configuration.fetcher.my_fetcher:
        class: My\OwnBundle\Configuration\MapFetcher\MyConfigurationFetcher
        arguments: array(]
        tags:
            - { name: idci_step.configuration.fetcher, alias: my_fetcher }
```

The alias "my_fetcher" will be the configuration fetcher identifier.

The doFetch function must return an array that should be in the following format:

```php
array(
    "name" => "MAP_NAME",
    "data" => array(), //MAP_DATA
    "steps" => array(
        "STEP_NAME" => array(
            "type" => "STEP_TYPE",
            "options" => array(), //STEP_OPTIONS
        ),
        ...
    ),
    "paths" => array(
        array(
            "type" => "PATH_TYPE",
            "options" => array(), //PATH_OPTIONS
        ),
        ...
    )
)
```

To check if your configuration fetcher are well configurated, you could list all them:

```sh
$ php app/console container:debug | grep "idci_step\.configuration\.fetcher\."
```
