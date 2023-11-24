JsConfirmPathEventAction
========================

How to use?
-----------

If you want to display an alert box with a defined message when the field name is empty when user wants to pass to next step:
```yaml
paths:
    -
        type: 'single'
        options:
            source: 'step1'
            destination: 'step2'
            events:
                form.pre_set_data:
                    -
                        action: 'js_confirm'
                        name: 'confirm_continue_with_empty_field'
                        parameters:
                            conditions:
                                -
                                    message: 'The field “Name” is empty. Click “cancel“ to add one or “ok“ to proceed.'
                                    observed_fields:
                                        name: ''
```