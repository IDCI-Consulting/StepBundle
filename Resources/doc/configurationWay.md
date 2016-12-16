Configuration way
=================

In the previous documentations parts we showed you how to implement Maps in php code.
We strongly recomment to put you'r code in configurations files.

This part will show how to do that.


```yaml
# app/config/config.yml
idci_step:
    maps:
        subscription:
            name: 'subscription'
            steps:
                personal:
                    type: 'form'
                    options:
                        title: 'Personal informations'
                        description: 'The personal data step'
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
                        destination: 'end'
                        next_options:
                            label: 'next'
                -
                    type: 'end'
                    options:
                        source: 'end'
                        next_options:
                            label: 'end'
```

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
     * @Route("/subscription/", name="test")
     *
     *
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function subscriptionAction(Request $request)
    {
        $flowData = array();

        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator(
                $request,
                'subscription', // The map key identifier
                array(),
                $flowData
            )
        ;

        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($this->generateUrl('test, $navigator->getUrlQueryParameters()));
        }

        if ($navigator->hasNavigated()) {
            return $this->redirect($this->generateUrl('test', $navigator->getUrlQueryParameters()));
        }

        if ($navigator->hasReturned()) {
            return $this->redirect($this->generateUrl('test', $navigator->getUrlQueryParameters()));
        }

        return array(
            'navigator' => $navigator
        );
    }
}
```

