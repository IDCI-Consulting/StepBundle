Installation
============

Using composer
--------------

```sh
$ composer require idci/step-bundle
```

Add dependencies in your `composer.json` file:
```json
"require": {
    ...
    "idci/step-bundle": "^4.0"
},
```

Install these new dependencies in your application using composer:
```sh
$ php composer.phar update
```

Register needed bundles in your enabled bundles file:
```php
// config/bundles.php
<?php
    return [
        // ...
        JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
        IDCI\Bundle\StepBundle\IDCIStepBundle::class => ['all' => true],
    ];
```

Add the following resources to your config.yml
```yml
// config/packages/idci_step.yaml
imports:
    - { resource: '@IDCIStepBundle/Resources/config/config.yml' }
```

Tests
-----

To execute unit tests, go inside the bundle directory and run the following command:
```sh
$ make phpunit
```
