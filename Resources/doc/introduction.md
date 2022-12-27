Introduction
============

StepBundle is a Symfony Bundle which allow you to simplify the creation of an interaction's workflow with a cybernaute.
Every screen (web page) that you will need to display, will be defined as a step, and you could linked every steps using paths.
At first, you have to define a map composed with steps and paths.

### What is a map ?

A map is a whole of process possibities composed of steps and paths.

### What is a step ?

A step is a potential passage's way.
Technicaly, It's based on Symfony form.


### What is a path ?

A path define a ride which has a step to origin and zero or more steps to destination.
Technicaly, It's a html button in a step to navigate to an other step.

### Legend

We will use diagram :
- arrays symbolize paths
- squares symbolize steps

![legend StepBundle diagram](images/legendStepBundle.png)

## Why use the StepBundle ?

It allows to define some simple process (one step, one path) and complex (more steps and more conditionnals's paths)
easily starting from a configuration which represents the map, using yaml or json.
Also, you can represente a map using directly the object's programmation but this is less maintainable and readable.

You can use this Bundle to create a contact form, a inscription process, a survey, an enquiry...

To continue, here's a technical presentation.
