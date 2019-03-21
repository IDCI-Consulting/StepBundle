Events
======

Create a Path Event Action Service
----------------------------------

If you want to add domain logic to your flow, you can do it by using EventAction.

If you want to execute action on path event, you'll have to create a PathEventAction Service and bind it to the path.

```php
// src\ExampleBundle\Path\Event\Action\ExamplePathEventAction.php
<?php

namespace ExampleBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use IDCI\Bundle\StepBundle\Path\Event\Action\AbstractPathEventAction;

class ExamplePathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
        // your action code with $parameters['varA'] here
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array(
                'varA',
            ))
            ->setAllowedTypes('varA', array('string'))
        ;
    }
}
```

```yaml
# app\config\service.yml
    services:
        ...
        examplebundle.path_event.action.example:
            class: ExampleBundle\Path\Event\Action\ExamplePathEventAction
            tags:
                - { name: idci_step.path_event_action, alias: example }
```

```yaml
# app\config\config.yml
idci_step:
    path_event_actions:
        example:
            parent: abstract
            description: ""
            extra_form_options: ~
```

Bind the event action to a path
--------------------------------

```php
// src\ExampleBundle\Controller\DefaultController.php
    $map = $this
        ...
        ->addPath(
            'single',
            array(
                'source' => 'stepA',
                'destination' => 'stepB'
                'events' => array(
                    'form.post_submit' => array(
                        array(
                            'action' => 'example',
                            'name' => 'example_return_value',
                            'parameters' => array(
                                'varA' => '{{ flow_data.data.stepA.varA }}',
                            )
                        )
                    )
                )
            )
        )
        ...
    ;
```

The doExecute method will be triggered as soon as symfony dispatches a form event.
The bind events names are the same as the symfony form events names

* pre_set_data
* post_set_data
* pre_submit
* submit
* post_submit

More informations in Symfony doc [Symfony form events](https://symfony.com/doc/current/form/events.html#registering-event-listeners-or-event-subscribers)

Create a Step Event Action Service
----------------------------------

If you want to execute action on step event, you'll have to create a StepEventAction Service and bind it to the step.
The logic is similar to path event actions logic. There are no advantages or inconvenients using step event actions or path event actions. As they are triggered by the same events, the principle is exactly the same (Path event actions and step event actions will be merged in the future).

```php
// src\ExampleBundle\Step\Event\Action\ExampleStepEventAction.php
<?php

namespace ExampleBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use IDCI\Bundle\StepBundle\Step\Event\Action\AbstractStepEventAction;

class ExampleStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = array())
    {
        // your action code with $parameters['varA'] here
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array(
                'varA',
            ))
            ->setAllowedTypes('varA', array('string'))
        ;
    }
}

```

```yaml
# app\config\service.yml
    services:
        ...
        examplebundle.step_event.example:
            class: ExampleBundle\Step\Event\Action\ExamplePathEventAction
            tags:
                - { name: idci_step.step_event_action, alias: example }
```

```yaml
# app\config\config.yml
idci_step:
    step_event_actions:
        example:
            parent: abstract
            description: ""
            extra_form_options: ~
```

Bind the event action to a step
--------------------------------

```php
// src\ExampleBundle\Controller\DefaultController.php
    $map = $this
        ...
        ->addStep('step_name', 'form', array(
                'title' => 'step title',
                'description' => 'step description',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('varA', 'text', array(
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
                ,
                'events' => array(
                    'form.post_submit' => array(
                        array(
                            'action' => 'example',
                            'name' => 'example_return_value',
                            'parameters' => array(
                                'varA' => '{{ flow_data.data.step_name.varA }}',
                            )
                        )
                    )
                )
        ))
        ...
    ;
```
