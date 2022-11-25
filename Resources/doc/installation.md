Installation
============

Symfony2 bundle that provides a step system in order to represent an highly customisable workflow


Installation
------------

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
        Gregwar\CaptchaBundle\GregwarCaptchaBundle::class => ['all' => true],
        JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
        IDCI\Bundle\ExtraFormBundle\IDCIExtraFormBundle::class => ['all' => true],
        IDCI\Bundle\StepBundle\IDCIStepBundle::class => ['all' => true],
    ];
```

Add the following resources to your config.yml
```yml
// config/packages/idci_step.yaml
imports:
    - { resource: '@IDCIExtraFormBundle/Resources/config/config.yml' }
    - { resource: '@IDCIStepBundle/Resources/config/config.yml' }
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
