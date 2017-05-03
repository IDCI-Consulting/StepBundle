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
        new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new IDCI\Bundle\ExtraFormBundle\IDCIExtraFormBundle(),
        new IDCI\Bundle\StepBundle\IDCIStepBundle(),
    );
}
```

Add the following resources to your config.yml
```yml
// app/config.yml
imports:
    ...
    - { resource: @IDCIExtraFormBundle/Resources/config/config.yml }
    - { resource: @IDCIStepBundle/Resources/config/config.yml }
```

Use the editor
--------------

([You can test the editor here](http://extra-form.labs.idci.fr/extra-step/editor))

If you need the api or the editor:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\RestBundle\FOSRestBundle(),
    );
}
```

Enable the serializer:

```yml
# app/config/config.yml

fos_rest:
    param_fetcher_listener: true # if you want to add configured types
    service:
        serializer: jms_serializer.serializer
```

Import the routes:
```yml
# app/config/routing.yml
idci_extraform_api:
    resource: "@IDCIExtraFormBundle/Controller/ApiController.php"
    type:     annotation
    prefix:   /api

idci_extraform_editor:
    resource: "@IDCIExtraFormBundle/Controller/EditorController.php"
    type:     annotation

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
{% endlbock %}
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
