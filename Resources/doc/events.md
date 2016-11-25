Events
======

Create a Path Event Action Service
----------------------------------

If you want to trigger action to path, you'll have to create a PathEventAction Service and stick it to the path.

```php
// src\ExampleBundle\Path\Event\Action\ExamplePathEventAction.php
<?php

namespace ExampleBundle\Path\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Path\Event\PathEventInterface;
use IDCI\Bundle\StepBundle\Path\Event\Action\AbstractPathEventAction;

class ExamplePathEventAction extends AbstractPathEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(PathEventInterface $event, array $parameters = array())
    {
        // your code action here with $parameters['varA']
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'varA',
            ))
            ->setAllowedTypes(array(
                'varA' => array('string'),
            ))
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
                - { name: idci_step.path_event.action, alias: example }
```

Stick the event action to a path
-------------------------------

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
                    'form.post_bind' => array(
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

The stickable events names are the same than the symfony form events

* pre_set_data
* post_set_data
* pre_bind
* bind
* post_bind

More informations in Symfony doc [Symfony form events](https://symfony.com/doc/current/form/events.html#registering-event-listeners-or-event-subscribers)

Create a Step Event Action Service
----------------------------------

If you want to trigger action to step, you'll have to create a StepEventAction Service and stick it to the step.

```php
// src\ExampleBundle\Step\Event\Action\ExampleStepEventAction.php
<?php

namespace ExampleBundle\Step\Event\Action;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use IDCI\Bundle\StepBundle\Step\Event\StepEventInterface;
use IDCI\Bundle\StepBundle\Step\Event\Action\AbstractStepEventAction;

class ExampleStepEventAction extends AbstractStepEventAction
{
    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepEventInterface $event, array $parameters = array())
    {
        // your code action here with $parameters['varA']
    }

    /**
     * {@inheritdoc}
     */
    protected function setDefaultParameters(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'varA',
            ))
            ->setAllowedTypes(array(
                'varA' => array('string'),
            ))
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
                - { name: idci_step.step_event.action, alias: example }
```

Stick the event to a step
-------------------------

```php
// src\ExampleBundle\Controller\DefaultController.php
    $map = $this
        ...
        ->addStep('step_name', 'form', array(
                'title'            => 'step title',
                'description'      => 'step description',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('varA', 'text', array(
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
                ,
                'events' => array(
                    'form.post_bind' => array(
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
