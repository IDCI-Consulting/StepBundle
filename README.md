StepBundle
==========

Symfony 4 bundle that provides a step system in order to represent an highly customisable workflow

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
        IDCI\Bundle\AssetLoaderBundle\IDCIAssetLoaderBundle::class => ['all' => true],
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

Use the editor
--------------

([You can test the editor here](http://extra-form.labs.idci.fr/extra-step/editor))

If you need the api or the editor:

```php
<?php
// config/bundles.php
return [
    // ...
    FOS\RestBundle\FOSRestBundle::class => ['all' => true],
];
```

Enable the serializer:

```yml
# config/packages/fos_rest.yaml

fos_rest:
    param_fetcher_listener: true # if you want to add configured types
    service:
        serializer: jms_serializer.serializer
```

Import the routes:
```yml
# config/routes/idci_step.yaml
idci_extraform_api:
    resource: "@IDCIExtraFormBundle/Controller/ApiController.php"
    type:     annotation
    prefix:   /api

idci_extrastep_api:
    resource: "@IDCIStepBundle/Controller/"
    type:     annotation
    prefix:   /api
```

Install the assets:
```sh
php app/console assets:install --symlink
```

The editor requires bootstrap and jquery >= 2.2.4. If you don't use it already in your project, just add the following lines in your views.

```twig
{% block javascripts %}
    {{ parent() }}
    <script type="text/javascript" src="{{ asset('bundles/idciextraform/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('bundles/idciextraform/js/vendor/bootstrap.min.js') }}"></script>
{% endblock %}

{% block stylesheets %}
    {{ parent() }}
    <link rel="stylesheet" type="text/css" href="{{ asset('bundles/idciextraform/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('bundles/idciextraform/css/bootstrap-theme.min.css') }}" />
{% endblock %}
```

Documentation
-------------

* [Introduction](Resources/doc/introduction.md)
* [Presentation](Resources/doc/presentation.md)
* [Configuration way](Resources/doc/configurationWay.md)
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
