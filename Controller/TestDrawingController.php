<?php

namespace IDCI\Bundle\StepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Description of TestDrawingController
 *
 * @author Antoine Ribola <antoine.ribola@gmail.com>
 */

/**
 * Test drawing controller.
 *
 * @Route("/drawstep")
 */
class TestDrawingController extends Controller 
{

    /**
     * @Route("/")
     * @Template("IDCIStepBundle:Test:testdrawing.html.twig")
     */
    public function drawstepAction() 
    {
        /*
        $json = 
            '{
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
            */
        
        $json = 
'{
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
                "data": {
                    "identity_id": "{{ \'{{ user.id|default(\'\') }}\' }}",
                    "identity_firstName": "{{ \'{{ user.raw.prenom|default(\'\') }}\' }}",
                    "identity_lastName": "{{ \'{{ user.raw.nom|default(\'\') }}\' }}",
                    "identity_email": "{{ \'{{ user.email|default(\'\') }}\' }}",
                    "identity_landingPhone": "{{ \'{{ user.raw.telephone|default(\'\') }}\' }}",
                    "identity_address1": "{{ \'{{ user.raw.adress_1|default(\'\') }}\' }}",
                    "identity_address2": "{{ \'{{ user.raw.adress_2|default(\'\') }}\' }}",
                    "identity_zipCode": "{{ \'{{ user.raw.code_postal|default(\'\') }}\' }}",
                    "identity_city": "{{ \'{{ user.raw.ville|default(\'\') }}\' }}",
                    "identity_country": "{{ \'{{ user.raw.pays|default(\'\') }}\' }}"
                },
                "@builder": {
                    "worker": "extra_form_builder",
                    "parameters": {
                        "configuration": {
                            "identity_id": {
                                "extra_form_type": "hidden",
                                "options": {
                                    "label": "Identifiant",
                                    "read_only": true,
                                    "error_bubbling": true
                                },
                                "constraints": [
                                    {
                                        "extra_form_constraint": "tms_eligibility_checker",
                                        "options": {
                                            "checkerPath": "participationlimit",
                                            "parameters": {
                                                "offer": "{{ participation.offer.reference }}",
                                                "customer": "{{ participation.offer.customer.reference }}",
                                                "status": "compliant",
                                                "processing_state": "in~~T~~I~~C~~R",
                                                "limit": "1"
                                            },
                                            "parameterKey": {
                                                "search": "identity_id"
                                            },
                                            "message": "Vous avez déjà participé à cette offre. Nous vous rappelons qu\'elle est limitée à une participation par foyer"
                                        }
                                    }
                                ]
                            },
                            "identity_firstName": {
                                "extra_form_type": "text",
                                "options": {
                                    "label": "Prénom*",
                                    "read_only": true,
                                    "error_bubbling": true
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
                                    "read_only": true
                                }
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
                            "name": "check_participation_unknown",
                            "action": "check_participation",
                            "parameters": {
                                "customer": "{{ participation.offer.customer.reference }}",
                                "offer": "{{ participation.offer.reference }}",
                                "limit": 1,
                                "user": "{{ \'{{ user.id|default(\'\') }}\' }}",
                                "processing_state": "!~~T~-~!~~I~-~!~~C~-~!~~R",
                                "modal_title": "Vous avez déjà participé à cette offre",
                                "modal_message": "Vous avez déjà participé à cette offre. Nous vous rappelons qu\'elle est limitée à une participation par foyer.<script >digifid._jquery(\'.reveal-modal a[href=#participations]\').on(\'click\', function () { digifid._jquery(this).parents(\'.reveal-modal\').foundation(\'reveal\', \'close\'); });</script>",
                                "modal_level": "info",
                                "modal_actions": [
                                    {
                                        "name": "Continuer ma participation",
                                        "href": "",
                                        "attr": {
                                            "class": "button close-reveal-modal"
                                        }
                                    },
                                    {
                                        "name": "Voir mon suivi de participation",
                                        "href": "#participations",
                                        "attr": {
                                            "class": "button"
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            "name": "check_participation_compliant",
                            "action": "check_participation",
                            "parameters": {
                                "customer": "{{ participation.offer.customer.reference }}",
                                "offer": "{{ participation.offer.reference }}",
                                "limit": 1,
                                "user": "{{ \'{{ user.id|default(\'\') }}\' }}",
                                "status": "compliant",
                                "processing_state": "in~~T~~I~~C~~R",
                                "modal_title": "Vous avez déjà participé à cette offre",
                                "modal_message": "Vous avez déjà participé à cette offre. Nous vous rappelons qu\'elle est limitée à une participation par foyer.<br><br> Nous vous invitons à consulter votre <a class= \'close-reveal-modal\' href=\'#participation\'>suivi de participation</a><script >digifid._jquery(\'.reveal-modal a\').on(\'click\', function () { digifid._jquery(this).parents(\'.reveal-modal\').foundation(\'reveal\', \'close\'); });</script>",
                                "modal_level": "info",
                                "modal_actions": [
                                    {
                                        "name": "Retour",
                                        "href": "#",
                                        "attr": {
                                            "class": "button"
                                        }
                                    }
                                ]
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
                "description": "Veuillez saisir le code-barres du produit acheté. <span data-tooltip data-options=\"append_to: #digifid div.current.step;disable_for_touch:true\" class=\"has-tip\" title=\"&lt;img src=\'http://media-manager.digifid.r.vb.sfdd78.fr/api/media/3087746855-1427294684-2158e2b765f50d24b3e008d8b52df33a-2263\' /&gt;\">help</span>",
                "previous_options": {
                    "label": "Précédent"
                },
                "display_title": false,
                "js": "(function(){var $=digifid._jquery;$(\'#digifid .step input\').keyup(function(){var value=$(this).val(),mapping={\'3572731300560\':\'Modilac OEBA 2\'};$(this).siblings(\'.input-info\').remove();if(undefined!==mapping[value]){$(this).before(\'<span class=\"input-info\"></span>\');$(this).prev(\'.input-info\').html(mapping[value]);}});})();",
                "@builder": {
                    "worker": "extra_form_builder",
                    "parameters": {
                        "configuration": {
                            "purchase-1_product-1_ean": {
                                "extra_form_type": "text",
                                "options": {
                                    "label": "Code-barres produit *",
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
                                                "3572731300560"
                                            ],
                                            "message": "Produit introuvable."
                                        }
                                    }
                                ]
                            },
                            "benefit-1_benefitId": {
                                "extra_form_type": "hidden",
                                "options": {
                                    "attr": {
                                        "value": 6
                                    }
                                }
                            }
                        }
                    }
                }
            }
        },
        "piece_img": {
            "type": "form",
            "options": {
                "title": "Photo de mon ticket de caisse",
                "description": "Veuillez télécharger votre ticket de caisse en veillant à ce que les éléments suivants soient bien lisibles :</p><br /><ul><li>Le nom du magasin</li><li>La date et l\'heure de votre achat</li><li>Le nom du produit acheté ainsi que son montant</li><li>Le montant total (TTC) de votre ticket</li></ul><br /><p>Format accepté : jpg, png, gif, pdf, tiff I poids maximum 3Mo<br />Important : Si votre ticket de caisse est trop long, pliez-le en accordéon de façon à faire apparaître le haut et le bas du ticket, et votre achat.</p>",
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
                                    "label": "Ticket de caisse*"
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
                            }
                        }
                    }
                }
            }
        },
        "product_img": {
            "type": "form",
            "options": {
                "title": "Photo de mon produit",
                "description": "Veuillez télécharger la photo de votre produit.",
                "previous_options": {
                    "label": "Précédent"
                },
                "display_title": false,
                "@builder": {
                    "worker": "extra_form_builder",
                    "parameters": {
                        "configuration": {
                            "purchase-1_proof-2": {
                                "extra_form_type": "tms_media_upload",
                                "options": {
                                    "label": "Image produit*"
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
                            }
                        }
                    }
                }
            }
        },
        "iban": {
            "type": "form",
            "options": {
                "title": "Mon IBAN",
                "description": "Veuillez renseigner vos coordonnées bancaires.",
                "previous_options": {
                    "label": "Précédent"
                },
                "display_title": false,
                "@builder": {
                    "worker": "extra_form_builder",
                    "parameters": {
                        "configuration": {
                            "bank_accountIban": {
                                "extra_form_type": "iban",
                                "options": {
                                    "label": "IBAN*"
                                },
                                "constraints": [
                                    {
                                        "extra_form_constraint": "not_blank",
                                        "options": {
                                            "message": "La saisie de l\'IBAN est obligatoire."
                                        }
                                    },
                                    {
                                        "extra_form_constraint": "regex",
                                        "options": {
                                            "pattern": "#^FR\\d\\d(\\d|[A-Za-z]){23}$#",
                                            "match": 1,
                                            "message": "Merci de renseigner correctement les champs obligatoires."
                                        }
                                    },
                                    {
                                        "extra_form_constraint": "iban",
                                        "options": {
                                            "message": "Merci de renseigner un IBAN valide."
                                        }
                                    },
                                    {
                                        "extra_form_constraint": "tms_eligibility_checker",
                                        "options": {
                                            "checkerPath": "participationlimit",
                                            "parameters": {
                                                "offer": "oeba",
                                                "status": "compliant",
                                                "processing_state": "in~~T~~I~~C~~R",
                                                "limit": "1"
                                            },
                                            "parameterKey": {
                                                "search": "bank_accountIban"
                                            },
                                            "message": "Votre IBAN exite déjà."
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
                "destination": "iban",
                "next_options": {
                    "label": "Suivant"
                }
            }
        },
        {
            "type": "single",
            "options": {
                "source": "iban",
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
                            "action": "create_participation",
                            "parameters": {
                                "customer": "{{ participation.offer.customer.reference }}",
                                "offer": "{{ participation.offer.reference }}",
                                "operation": "{{ participation.offer.operation.reference }}",
                                "source": "digifid - webConsumerApp - modilac.odr.fr"
                            }
                        },
                        {
                            "action": "notify",
                            "parameters": {
                                "type": "modal",
                                "options": {
                                    "title": "FELICITATIONS !",
                                    "message": "<p>Nous vous remercions de votre participation à cette offre.<br />Un e-mail de confirmation va vous être envoyé.</p><br /><p>Votre dossier va à présent être traité par nos services dans les meilleurs delais.</p><br /><p>Si votre dossier est conforme, vous recevrez votre remboursement dans un délai maximum de 15 jours.<br />Si votre dossier est non-conforme, vous recevrez un e-mail vous précisant le motif de non-conformité.</p>"
                                }
                            }
                        },
                        {
                            "action": "notify",
                            "parameters": {
                                "type": "email",
                                "options": {
                                    "notifierAlias": "modilac",
                                    "to": "{{ \'{{ user.email|default(\'nabil.mansouri@tessi.fr\') }}\' }}",
                                    "subject": "Votre participation à l’offre {{ participation.offer.shortDescription|raw }}",
                                    "message": "Votre participation à l’offre « {{ participation.offer.shortDescription }} » est bien enregistrée, nous vous en remercions.</br></br>Après vérification de la conformité des éléments de votre dossier, vous serez informé par e-mail de la suite donnée à votre participation. </br></br>Pour plus d’informations, rendez-vous dans votre suivi de participation.</br></br>A très bientôt.</br>L’équipe Modilac</br></br><i>Ce message vous est envoyé automatiquement, merci de ne pas y répondre.</i>",
                                    "htmlMessage": "Votre participation à l’offre « {{ participation.offer.shortDescription }} » est bien enregistrée, nous vous en remercions.</br></br>Après vérification de la conformité des éléments de votre dossier, vous serez informé par e-mail de la suite donnée à votre participation. </br></br>Pour plus d’informations, rendez-vous dans votre suivi de participation.</br></br>A très bientôt.</br>L’équipe Modilac</br></br><i>Ce message vous est envoyé automatiquement, merci de ne pas y répondre.</i>"
                                }
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
