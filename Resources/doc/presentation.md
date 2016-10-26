Presentation
============

Creating a Simple Map
---------------------

Building the Map
-----------------

We have to create a map before rendre it.
For now, this can all be done from inside a controller.

```php
// src/AppBundle/Controller/DefaultController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/contact/", name="test")
     *
     *
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function contactAction(Request $request)
    {
        $map = $this
            ->get('idci_step.map.builder.factory')
            ->createNamedBuilder('test map')
            ->addStep('intro', 'html', array(
                'title'       => 'Introduction',
                'description' => 'The first step',
                'content'     => '<h1>My content</h1>',
            ))
            ->addStep('personal', 'form', array(
                'title'            => 'Personal information',
                'description'      => 'The personal data step',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('first_name', 'text', array(
                        'constraints' => array(
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                        )
                    ))
                    ->add('last_name', 'text')
                ,
            ))
            ->addStep('purchase', 'form', array(
                'title'       => 'Purchase information',
                'description' => 'The purchase data step',
                'builder' => $this->get('form.factory')->createBuilder()
                    ->add('item', 'text')
                    ->add('purchase_date', 'datetime')
                ,
            ))
            ->getMap()
        ;

        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator(
                $request,
                $map,
                array(),
                json_decode('{
                    "data": {
                        "personal":{"first_name":"John","last_name":"DOE"},
                        "purchase":{"item":"Something","purchase_date":{"date":{"month":"10","day":"10","year":"2010"},"time":{"hour":"10","minute":"10"}}}
                    }
                }', true)
            )
        ;

        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($navigator->getFinalDestination());
        }
        if ($navigator->hasNavigated()) {
            return $this->redirect($this->generateUrl('test'));
        }
        if ($navigator->hasReturned()) {
            return $this->redirect($this->generateUrl('test'));
        }

        return array('navigator' => $navigator);
    }
}
```

Rendering the Map
------------------

Handling Map Submissions
-------------------------

Built-in Field Types
--------------------
