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
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\StepBundle\IDCIStepBundle(),
    );
}
```


Documentation
-------------

* [Introduction](Resources/doc/introduction.md)
* [Architecture](Resources/doc/architecture.md)
* [StepBuilder](Resources/doc/step_builder.md)
* [StepType](Resources/doc/step_type.md)
* [Configuration reference](Resources/doc/configuration_reference.md)


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
