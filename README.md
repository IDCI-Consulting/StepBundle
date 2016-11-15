StepBundle
==========

Symfony2 bundle that provides a step system in order to represent an highly customisable workflow


Installation
------------

Add dependencies in your `composer.json` file:
```json
"require": {
    ...
    "idci/step-bundle": "dev-master"
},
```

Install these new dependencies in your application using composer:
```sh
$ php composer.phar update
```

Register needed bundles in your application kernel:
```php
// app/AppKernel.php
<?php

public function registerBundles()
{
    $bundles = array(
        // ...
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\Bundle\StepBundle\IDCIStepBundle(),
    );
}
```

Add the IDCIStepBundle resources to your config.yml
```yml
// app/config.yml
imports:
    ...
    - { resource: @IDCIStepBundle/Resources/config/config.yml }
```

Documentation
-------------

* [Introduction](Resources/doc/introduction.md)
* [Presentation](Resources/doc/presentation.md)
* [Events](Resources/doc/events.md)
* [Configuration](Resources/doc/configuration.md)


Tests
-----

Install bundle dependencies:
```sh
$ php composer.phar update
```

To execute unit tests:
```sh
$ phpunit --coverage-text
```
