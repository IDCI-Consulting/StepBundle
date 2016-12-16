Configurations
==============

This page show a exhaustive example configuration
-------------------------------------------------

TODO fix link
This file is inspired by the Idci articles [ IDCI StepBundle articles ](https://www.idci-consulting.fr)

```yaml
idci_step:
    maps:
        subscription:
            name: 'subscription'
            steps:
                personal:
                    type: 'form'
                    options:
                        title: 'Personal informations'
                        display_title: false
                        description: 'The personal data step'
                        previous_options:
                             label: 'Previous step'
                             attr:
                                 class: 'col-md-3'

                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'first_name'
                                        type: 'text'
                                    -
                                        name: 'last_name'
                                        type: 'text'
                                    -
                                        name: 'phone_number'
                                        type: 'text'
                                    -
                                        name: 'email'
                                        type: 'text'
                                    -
                                        name: 'zip_code'
                                        type: 'text'
                                    -
                                        name: 'city'
                                        type: 'text'
                cursus:
                    type: 'form'
                    options:
                        title: 'Your course'
                        description: 'Course and studying city'
                        js: '// The javascript code to automatically exec'
                        prevent_previous: true
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'diploma_date'
                                        type: 'IDCI\ContactBundle\Form\Type\DiplomaDateFormType'
                                    -
                                        name: 'university_level'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            label: 'What is your university level ?'
                                            choices:
                                                bac1: 'Bac+1'
                                                bac2: 'Bac+2'
                                                bac3: 'Bac+3'
                                                bac4: 'Bac+4'
                                    -
                                        name: 'study_city'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            label: 'Where do you want to study ?'
                                            choices:
                                                Lyon: 'Lyon'
                                                Paris: 'Paris'
                cursus_lyon:
                    type: 'form'
                    options:
                        title: 'Welcome to Lyon, we hope you like quenelle of pike !'
                        description: 'The differents courses in Lyon'
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'cursus'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            choices:
                                                bts_comm: 'HDN Communication'
                                                bts_mktg: 'HDN Marketing'
                                                bachelor_comm: 'Bachelor Communication'
                                                bachelor_mktg: 'Bachelor Marketing'
                                                bachelor_digital: 'Bachelor Digital'
                                                master_comm: 'Master degree Communication'
                                                master_mktg: 'Master degree Marketing'
                                                master_digital: 'Master degree Digital'
                cursus_paris:
                    type: 'form'
                    options:
                        title: 'Welcome to Paris, we hope you like the subway !'
                        description: 'The differents courses in Paris'
                        @builder:
                            worker: 'form_builder'
                            parameters:
                                fields:
                                    -
                                        name: 'cursus'
                                        type: 'Symfony\Component\Form\Extension\Core\Type\ChoiceType'
                                        options:
                                            choices:
                                                bts_comm: 'HDN Communication'
                                                bts_mktg: 'HDN Marketing'
                                                bts_business: 'HDN Business'
                                                bachelor_comm: 'Bachelor Communication'
                                                bachelor_mktg: 'Bachelor Marketing'
                                                bachelor_business: 'Bachelor Business'
                                                master_comm: 'Master degree Communication'
                                                master_mktg: 'Master degree Marketing'
                                                master_business: 'Master degree Digital'
                end:
                    type: 'html'
                    options:
                        title: 'Inscription online'
                        description: 'Inscription done'
                        content: 'Thank you ! Please, join the proof of entitlement. Inscriptions.school@school.com'
            paths:
                -
                    type: 'single'
                    options:
                        source: 'personal'
                        destination: 'cursus'
                        next_options:
                            label: 'next'
                                attr:
                                    class: 'next col-md-3'
                -
                    type: 'conditional_destination'
                    options:
                        source: 'cursus'
                        destinations:
                            cursus_lyon: '{{flow_data.data.cursus.study_city == 'Lyon' }}'
                            cursus_paris: '{{flow_data.data.cursus.study_city == 'Paris' }}'
                        default_destination: 'cursus_paris'
                        next_options:
                            label: 'next'
                -
                    type: 'single'
                    options:
                        source: 'cursus_lyon'
                        destination: 'end'
                        next_options:
                            label: 'next'
                -
                    type: 'single'
                    options:
                        source: 'cursus_paris'
                        destination: 'end'
                        next_options:
                            label: 'next'
                -
                    type: 'end'
                    options:
                        source: 'end'
                        next_options:
                            label: 'end'
                        events:
                            form.post_bind:
                                -
                                    action: 'path_event_action_name'
                                    name: 'retrieved_data_value_name'
                                    parameters:
                                        first_name: '{{ flow_data.data.personnal.first_name|raw }}'
                                        last_name: '{{ flow_data.data.personnal.last_name|raw }}'
```

