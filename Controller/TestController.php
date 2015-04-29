<?php

namespace IDCI\Bundle\StepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Test controller.
 *
 * @Route("/step")
 */
class TestController extends Controller {

    /**
     * Test.
     *
     * @Route("/", name="idci_step_test_1")
     * @Method({"GET", "POST"})
     * @Template("IDCIStepBundle:Test:test.html.twig")
     */
    public function test1Action(Request $request) {
        /*
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
          'previous_options' => array(
          'label' => 'Back to first step',
          ),
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
          ->addStep('fork1', 'form', array(
          'title'       => 'Fork1 information',
          'description' => 'The fork1 data step',
          'builder' => $this->get('form.factory')->createBuilder()
          ->add('fork1_data', 'textarea')
          ,
          ))
          ->addStep('fork2', 'form', array(
          'title'       => 'Fork2 information',
          'description' => 'The fork2 data step',
          'builder' => $this->get('form.factory')->createBuilder()
          ->add('fork2_data', 'textarea')
          ,
          ))
          ->addStep('end', 'html', array(
          'title'       => 'The end',
          'description' => 'The last data step',
          'content'     => '<h1>The end</h1>',
          ))
          ->addPath(
          'single',
          array(
          'source'       => 'intro',
          'destination'  => 'personal',
          'next_options' => array(
          'label' => 'next',
          ),
          )
          )
          ->addPath(
          'conditional_destination',
          array(
          'source'        => 'personal',
          'destinations'  => array(
          'purchase'  => array(
          'rules' => array()
          ),
          'fork2'     => array(
          'rules' => array()
          ),
          )
          )
          )
          ->addPath(
          'single',
          array(
          'source'      => 'purchase',
          'destination' => 'fork1'
          )
          )
          ->addPath(
          'single',
          array(
          'source'       => 'purchase',
          'destination'  => 'fork2',
          'next_options' => array(
          'label' => 'next p',
          ),
          )
          )
          ->addPath(
          'single',
          array(
          'source'       => 'fork1',
          'destination'  => 'end',
          'next_options' => array(
          'label' => 'next f',
          ),
          )
          )
          ->addPath(
          'single',
          array(
          'source'       => 'fork2',
          'destination'  => 'end',
          'next_options' => array(
          'label' => 'last',
          ),
          )
          )
          ->addPath(
          'end',
          array(
          'source'           => 'end',
          'next_options'     => array(
          'label' => 'end',
          ),
          )
          )
          ->getMap()
          ;

          $navigator = $this
          ->get('idci_step.navigator.factory')
          ->createNavigator(
          $request,
          $map,
          array(),
          json_decode('{
          "personal":{"first_name":"John","last_name":"DOE"},
          "purchase":{"item":"Something","purchase_date":{"date":{"month":"10","day":"10","year":"2010"},"time":{"hour":"10","minute":"10"}}}
          }', true)
          )
          ;
         */

        $navigator = $this
            ->get('idci_step.navigator.factory')
            ->createNavigator(
            $request, 'participation_map', array(), json_decode('{ "personal":{"first_name": "controller_first_name"} }', true)
            )
        ;


        if ($navigator->hasFinished()) {
            $navigator->clear();

            return $this->redirect($this->generateUrl('idci_step_test_1'));
        }

        if ($navigator->hasNavigated()) {
            return $this->redirect($this->generateUrl('idci_step_test_1'));
        }

        if ($navigator->hasReturned()) {
            return $this->redirect($this->generateUrl('idci_step_test_1'));
        }

        return array('navigator' => $navigator);
    }

    /**
     * @Route("/drawstep")
     * @Template("IDCIStepBundle:Test:testdrawing.html.twig")
     */
    public function drawstepAction() {
        $json = '{
 "name": "modilac",
 "steps": {
 "personal": {
 "type": "form",
 "options": {
 "title": "Mes coordonnées",
 "description": "Vérifiez que vos coordonnées soient correctes.<br />Pour les modifier, cliquer sur le bouton \"modifier mes coordonnées\" pour revenir à votre compte.",
 "previous_options": {
 "label": "Précédent"
 },
 "display_title": false,
 "@builder": {
 "worker": "extra_form_builder",
 "parameters": {
 "configuration": {
 "identity_firstName": {
 "extra_form_type": "text",
 "options": {
 "label": "Prénom*",
 "read_only": true
 }
 },
 "identity_address1": {
 "extra_form_type": "text",
 "options": {
 "label": "Adresse*",
 "read_only": true,
 "attr": {
 "class": "boxed"
 }
 }
 },
 "identity_lastName": {
 "extra_form_type": "text",
 "options": {
 "label": "Nom*",
 "read_only": true
 }
 },
 "identity_address2": {
 "extra_form_type": "text",
 "options": {
 "label": "Complément d’adresse",
 "read_only": true,
 "attr": {
 "class": "boxed"
 }
 }
 },
 "identity_email": {
 "extra_form_type": "email",
 "options": {
 "label": "E-mail*",
 "read_only": true
 }
 },
 "identity_zipCode": {
 "extra_form_type": "text",
 "options": {
 "label": "CP*",
 "read_only": true
 }
 },
 "identity_landingPhone": {
 "extra_form_type": "text",
 "options": {
 "label": "Téléphone*",
 "max_length": 10
 },
 "constraints": [
 {
 "extra_form_constraint": "not_null",
 "options": {
 "message": "Merci de renseigner correctement votre numéro de téléphone"
 }
 },
 {
 "extra_form_constraint": "regex",
 "options": {
 "pattern": "#^0[0-9]{9}$#",
 "match": true,
 "message": "Merci de renseigner correctement votre numéro de téléphone"
 }
 }
 ]
 },
 "identity_city": {
 "extra_form_type": "text",
 "options": {
 "label": "Ville*",
 "read_only": true
 }
 },
 "identity_country": {
 "extra_form_type": "text",
 "options": {
 "label": "Pays*",
 "read_only": true
 }
 }
 }
 }
 },
 "events": {
 "form.pre_set_data": [
 {
 "action": "add_link",
 "parameters": {
 "link_options": {
 "label": "Modifier mes coordonnées",
 "href": "/user/{{ \'{{ user.raw.id|default(\'-\') }}\' }}/edit",
 "attr": {
 "class": "button"
 }
 }
 }
 }
 ],
 "form.post_set_data": [
 {
 "action": "change_data",
 "parameters": {
 "fields": {
 "identity_firstName": "{{ \'{{ user.raw.prenom|default(\'\') }}\' }}",
 "identity_lastName": "{{ \'{{ user.raw.nom|default(\'\') }}\' }}",
 "identity_email": "{{ \'{{ user.email|default(\'\') }}\' }}",
 "identity_landingPhone": "{{ \'{{ user.raw.telephone|default(\'\') }}\' }}",
 "identity_address1": "{{ \'{{ user.raw.adress_1|default(\'\') }}\' }}",
 "identity_address2": "{{ \'{{ user.raw.adress_2|default(\'\') }}\' }}",
 "identity_zipCode": "{{ \'{{ user.raw.code_postal|default(\'\') }}\' }}",
 "identity_city": "{{ \'{{ user.raw.ville|default(\'\') }}\' }}",
 "identity_country": "{{ \'{{ user.raw.pays|default(\'\') }}\' }}"
 }
 }
 }
 ]
 }
 }
 },
 "product": {
 "type": "form",
 "options": {
 "title": "Mon achat",
 "description": "Veuillez saisir les codes-barres des produits achetés. <span data-tooltip data-options = \"append_to: #digifid div.current.step;disable_for_touch:true\" class=\"has-tip\" title=\"&lt;img src=\'http://modilac.preprod.odr.fr/bundles/themes/modilac/tmswebapibundle/images/exempleCB.jpg\' /&gt;\">help</span>",
 "previous_options": {
"label": "Précédent"
},
 "display_title": false,
 "js": "(function(){var $=digifid._jquery;$(\'#digifid .step input\').keyup(function(){var value=$(this).val(),mapping={\'3572731340825\':\'Modilac Expert Doucéa 2\',\'3572731140029\':\'Modilac Doucéa 2\'};$(this).siblings(\'.input-info\').remove();if(undefined!==mapping[value]){$(this).before(\'<span class=\"input-info\"></span>\');$(this).prev(\'.input-info\').html(mapping[value]);}});})();",
 "@builder": {
"worker": "extra_form_builder",
 "parameters": {
"configuration": {
"purchase-1_product-1_ean": {
"extra_form_type": "text",
 "options": {
"label": "Code-barres produit 1*",
 "max_length": 13,
 "required": true
},
 "constraints": [
{
"extra_form_constraint": "not_blank",
 "options": {
"message": "La saisie du code-barres est obligatoire."
}
},
 {
"extra_form_constraint": "isbn",
 "options": {
"isbn13": true,
 "isbn13Message": "Code-barres incorrect."
}
},
 {
"extra_form_constraint": "choice",
 "options": {
"choices": [
"3572731340825",
 "3572731140029"
],
 "message": "Produit introuvable."
}
}
]
},
 "purchase-1_product-2_ean": {
"extra_form_type": "text",
 "options": {
"label": "Code-barres produit 2*",
 "max_length": 13,
 "required": true
},
 "constraints": [
{
"extra_form_constraint": "not_blank",
 "options": {
"message": "La saisie du code-barres est obligatoire."
}
},
 {
"extra_form_constraint": "isbn",
 "options": {
"isbn13": true,
 "isbn13Message": "Code-barres incorrect."
}
},
 {
"extra_form_constraint": "choice",
 "options": {
"choices": [
"3572731340825",
 "3572731140029"
],
 "message": "Produit introuvable."
}
}
]
},
 "purchase-1_product-3_ean": {
"extra_form_type": "text",
 "options": {
"label": "Code-barres produit 3*",
 "max_length": 13,
 "required": true
},
 "constraints": [
{
"extra_form_constraint": "not_blank",
 "options": {
"message": "La saisie du code-barres est obligatoire."
}
},
 {
"extra_form_constraint": "isbn",
 "options": {
"isbn13": true,
 "isbn13Message": "Code-barres manquant ou incorrect."
}
},
 {
"extra_form_constraint": "choice",
 "options": {
"choices": [
"3572731340825",
 "3572731140029"
],
 "message": "Produit introuvable."
}
}
]
}
}
}
}
}
},
 "piece_img": {
"type": "form",
 "options": {
"title": "Photo de mes tickets de caisse",
 "description": "Veuillez télécharger votre ticket de caisse unique dans le cas où vous avez acheté vos 3 produits simultanément ou vos différents tickets de caisse dans le cas d’achats différés tout en veillant à ce que les éléments suivants soient bien lisibles :</p><br /><ul><li>Le nom du magasin</li><li>La date et l\'heure de votre achat</li><li>Le nom du produit acheté ainsi que son montant</li><li>Le montant total (TTC) de votre ticket</li></ul><br /><p>Format accepté : jpg, png, gif, pdf, tiff I poids maximum 3Mo<br />Important : Si votre ticket de caisse est trop long, pliez-le en accordéon de façon à faire apparaître le haut et le bas du ticket, et votre achat.</p>",
 "previous_options": {
"label": "Précédent"
},
 "display_title": false,
 "@builder": {
"worker": "extra_form_builder",
 "parameters": {
"configuration": {
"purchase-1_proof-1": {
"extra_form_type": "tms_media_upload",
 "options": {
"label": "Ticket de caisse 1*",
 "required": false
},
 "constraints": [
{
"extra_form_constraint": "tms_media_upload_not_blank",
 "options": {
"message": "Merci de télécharger les fichiers obligatoires."
}
},
 {
"extra_form_constraint": "tms_media_upload_file",
 "options": {
"maxSize": "3M",
 "maxSizeMessage": "Poids incorrect : Le poids de votre fichier dépasse la limite autorisée de 3Mo. Merci de réduire son poids et recommencer.",
 "mimeTypes": [
"image\/gif",
 "image\/png",
 "image\/jpeg",
 "image\/tiff",
 "application\/pdf"
],
 "mimeTypesMessage": "Format non reconnu : Le format de votre fichier est incorrect. Merci de bien vérifier la liste des formats acceptés."
}
}
]
},
 "purchase-1_proof-2": {
"extra_form_type": "tms_media_upload",
 "options": {
"label": "Ticket de caisse 2",
 "required": false
},
 "constraints": [
{
"extra_form_constraint": "tms_media_upload_file",
 "options": {
"maxSize": "3M",
 "maxSizeMessage": "Poids incorrect : Le poids de votre fichier dépasse la limite autorisée de 3Mo. Merci de réduire son poids et recommencer.",
 "mimeTypes": [
"image\/gif",
 "image\/png",
 "image\/jpeg",
 "image\/tiff",
 "application\/pdf"
],
 "mimeTypesMessage": "Format non reconnu : Le format de votre fichier est incorrect. Merci de bien vérifier la liste des formats acceptés."
}
}
]
},
 "purchase-1_proof-3": {
"extra_form_type": "tms_media_upload",
 "options": {
"label": "Ticket de caisse 3",
 "required": false
},
 "constraints": [
{
"extra_form_constraint": "tms_media_upload_file",
 "options": {
"maxSize": "3M",
 "maxSizeMessage": "Poids incorrect : Le poids de votre fichier dépasse la limite autorisée de 3Mo. Merci de réduire son poids et recommencer.",
 "mimeTypes": [
"image\/gif",
 "image\/png",
 "image\/jpeg",
 "image\/tiff",
 "application\/pdf"
],
 "mimeTypesMessage": "Format non reconnu : Le format de votre fichier est incorrect. Merci de bien vérifier la liste des formats acceptés."
}
}
]
}
}
}
}
}
},
 "product_img": {
"type": "form",
 "options": {
"title": "Photo de mes produits",
 "description": "Veuillez télécharger la photo de vos produits.</br>Vous pouvez prendre 1 photo de vos 3 achats ou 3 photos différentes.",
 "previous_options": {
"label": "Précédent"
},
 "display_title": false,
 "@builder": {
"worker": "extra_form_builder",
 "parameters": {
"configuration": {
"purchase-1_proof-4": {
"extra_form_type": "tms_media_upload",
 "options": {
"label": "Image produit 1*",
 "required": false
},
 "constraints": [
{
"extra_form_constraint": "tms_media_upload_not_blank",
 "options": {
"message": "Merci de télécharger les fichiers obligatoires."
}
},
 {
"extra_form_constraint": "tms_media_upload_file",
 "options": {
"maxSize": "3M",
 "maxSizeMessage": "Poids incorrect : Le poids de votre fichier dépasse la limite autorisée de 3Mo. Merci de réduire son poids et recommencer.",
 "mimeTypes": [
"image\/gif",
 "image\/png",
 "image\/jpeg",
 "image\/tiff",
 "application\/pdf"
],
 "mimeTypesMessage": "Format non reconnu : Le format de votre fichier est incorrect. Merci de bien vérifier la liste des formats acceptés."
}
}
]
},
 "purchase-1_proof-5": {
"extra_form_type": "tms_media_upload",
 "options": {
"label": "Image produit 2",
 "required": false
},
 "constraints": [
{
"extra_form_constraint": "tms_media_upload_file",
 "options": {
"maxSize": "3M",
 "maxSizeMessage": "Poids incorrect : Le poids de votre fichier dépasse la limite autorisée de 3Mo. Merci de réduire son poids et recommencer.",
 "mimeTypes": [
"image\/gif",
 "image\/png",
 "image\/jpeg",
 "image\/tiff",
 "application\/pdf"
],
 "mimeTypesMessage": "Format non reconnu : Le format de votre fichier est incorrect. Merci de bien vérifier la liste des formats acceptés."
}
}
]
},
 "purchase-1_proof-6": {
"extra_form_type": "tms_media_upload",
 "options": {
"label": "Image produit 3",
 "required": false
},
 "constraints": [
{
"extra_form_constraint": "tms_media_upload_file",
 "options": {
"maxSize": "3M",
 "maxSizeMessage": "Poids incorrect : Le poids de votre fichier dépasse la limite autorisée de 3Mo. Merci de réduire son poids et recommencer.",
 "mimeTypes": [
"image\/gif",
 "image\/png",
 "image\/jpeg",
 "image\/tiff",
 "application\/pdf"
],
 "mimeTypesMessage": "Format non reconnu : Le format de votre fichier est incorrect. Merci de bien vérifier la liste des formats acceptés."
}
}
]
}
}
}
}
}
},
 "confirmation": {
"type": "html",
 "options": {
"title": "Confirmation de participation",
 "previous_options": {
"label": "Précédent"
},
 "description": "Pour confirmer votre participation à cette offre, veuillez cliquer sur le bouton ci-dessous.",
 "content": "<p>Important : Après cette étape, votre participation sera enregistrée et aucune modification ne pourra être effectuée.</p>"
}
}
},
"paths": [
{
"type": "single",
"options": {
"source": "personal",
"destination": "product",
"next_options": {
"label": "Suivant"
}
}
},
{
"type": "single",
"options": {
"source": "product",
"destination": "piece_img",
"next_options": {
"label": "Suivant"
}
}
},
{
"type": "single",
"options": {
"source": "piece_img",
"destination": "product_img",
"next_options": {
"label": "Suivant"
}
}
},
{
"type": "single",
"options": {
"source": "product_img",
"destination": "confirmation",
"next_options": {
"label": "Suivant"
}
}
},
{
"type": "end",
"options": {
"source": "confirmation",
"next_options": {
"label": "Je valide ma demande"
},
"events": {
"form.post_bind": [
{
"action": "participation",
"parameters": {
"customer": "{{ participation.offer.customer.reference }}",
"offer": "{{ participation.offer.reference }}",
"operation": "{{ participation.offer.operation.reference }}",
"source": "digifid - webConsumerApp - modilac.odr.fr",
"message_ok": "<p class=\'title\'>FELICITATIONS !</p><br /><p>Nous vous remercions de votre participation à cette offre.<br />Un e-mail de confirmation va vous être envoyé.</p><br /><p>Votre dossier va à présent être traité par nos services dans les meilleurs delais.</p><br /><p>Si votre dossier est conforme, vous recevrez un email sous 4 à 8 semaines vous proposant 3 activités au choix proche de chez vous.<br />Si votre dossier est non-conforme, vous recevrez un e-mail vous précisant le motif de non-conformité.</p><a class=\'close-reveal-modal\'>&#215;</a>",
"message_ko": "<p class=\'title\'>UNE ERREUR S’EST PRODUITE</p><br /><p>Nous vous prions de nous en excuser et de bien vouloir réessayer plus tard.</p>"
}
},
{
"action": "notification",
"parameters": {
"notifierAlias": "modilac",
"type": "email",
"to": "{{ \'{{ user.email|default(\'nabil.mansouri@tessi.fr\') }}\' }}",
"subject": "Votre participation à l’offre {{ participation.offer.shortDescription|raw }}",
"message": "Votre participation à l’offre « {{ participation.offer.shortDescription }} » est bien enregistrée, nous vous en remercions.</br></br>Après vérification de la conformité des éléments de votre dossier, vous serez informé par e-mail de la suite donnée à votre participation. </br></br>Pour plus d’informations, rendez-vous dans votre suivi de participation.</br></br>A très bientôt.</br>L’équipe Modilac</br></br><i>Ce message vous est envoyé automatiquement, merci de ne pas y répondre.</i>",
"htmlMessage": "Votre participation à l’offre « {{ participation.offer.shortDescription }} » est bien enregistrée, nous vous en remercions.</br></br>Après vérification de la conformité des éléments de votre dossier, vous serez informé par e-mail de la suite donnée à votre participation. </br></br>Pour plus d’informations, rendez-vous dans votre suivi de participation.</br></br>A très bientôt.</br>L’équipe Modilac</br></br><i>Ce message vous est envoyé automatiquement, merci de ne pas y répondre.</i>"
}
}
]
}
}
}
]
}';

        return array('json' => $json);
    }

}
