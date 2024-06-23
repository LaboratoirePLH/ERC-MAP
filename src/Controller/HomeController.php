<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Entity\Source;
use App\Entity\IndexRecherche;
use App\Form\ChercheurType;
use App\Form\ChangePasswordType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    public function __construct(string $fromEmail, string $fromName)
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, ManagerRegistry $doctrine)
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Request $request, ManagerRegistry $doctrine)
    {
        $user = $this->getUser();
        $locale = $request->getLocale();

        $counters = $doctrine->getRepository(IndexRecherche::class)->getHomeCounters(!$this->isGranted('ROLE_CONTRIBUTOR'));

        return $this->render('home/dashboard.html.twig', [
            'controller_name' => 'HomeController',
            'user_name'       => $this->isGranted('ROLE_USER') ? $user->getPrenomNom() : null,
            'webmapping_url'  => $this->getParameter('geo.app_url_' . $locale),
            'counters'        => $counters
        ]);
    }

    /**
     * @Route("/inactive_account", name="inactive_account")
     */
    public function inactiveAccount()
    {
        return $this->render('home/inactive_account.html.twig');
    }

    /**
     * @Route("/legal", name="legal")
     */
    public function legal(Request $request)
    {
        return $this->render('home/legal.html.twig', [
            'locale' => $request->getLocale()
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get("name");
            $email = $request->request->get("email");
            $object = $request->request->get("object");
            $refers_to = $request->request->get("refers_to");
            $refers_to_id = $request->request->get("refers_to_id");
            $message = $request->request->get("message");

            $mail = (new \Swift_Message($translator->trans('mails.contact.title')))
                ->setFrom([$this->fromEmail => $this->fromName])
                ->setTo($this->fromEmail)
                ->setReplyTo($email)
                ->setBody(
                    $this->renderView(
                        'email/contact.html.twig',
                        compact("name", "email", "object", "refers_to", "refers_to_id", "message")
                    ),
                    'text/html'
                );

            $mailer->send($mail);

            $request->getSession()->getFlashBag()->add('success', 'login_page.message.message_sent');

            return $this->redirectToRoute('home');
        }

        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'nav.contact']
            ]
        ]);
    }

    /**
     * @Route("/language/{lang}", name="language")
     */
    public function language($lang, Request $request, ManagerRegistry $doctrine)
    {
        if (!in_array($lang, ["fr", "en"])) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.invalid_locale');
        } else if ($this->isGranted('ROLE_USER')) {
            $user = $this->getUser();
            $user->setPreferenceLangue($lang);
            $doctrine->getManager()->flush();
        }
        $request->getSession()->set('_locale', $lang);
        $referer = $request->headers->get('referer');
        if ($referer == NULL) {
            $url = $this->generateUrl('home');
        } else {
            $url = $referer;
        }
        return $this->redirect($url);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $user = $this->getUser();

        $accountForm = $this->createForm(ChercheurType::class, $user);
        $accountForm->handleRequest($request);
        $passwordForm = $this->createForm(ChangePasswordType::class, null, [
            'repeat_error' => $translator->trans('chercheur.repeat_password_error')
        ]);
        $passwordForm->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($accountForm->isSubmitted() && $accountForm->isValid()) {
                $doctrine->getManager()->flush();

                // Message de confirmation
                $request->getSession()->getFlashBag()->add('success', 'chercheur.profile_edited');
                return $this->redirectToRoute('home');
            }
            if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
                if (password_verify($passwordForm['password']->getData(), $user->getPassword())) {
                    $newPassword = password_hash(
                        $passwordForm['new_password']->getData(),
                        PASSWORD_BCRYPT,
                        ['cost' => 15]
                    );
                    $user->setPassword($newPassword);
                    $doctrine->getManager()->flush();

                    $request->getSession()->getFlashBag()->add('success', 'chercheur.password_edited');
                    return $this->redirectToRoute('home');
                } else {
                    $passwordForm->get('password')
                        ->addError(
                            new FormError($translator->trans('chercheur.incorrect_password'))
                        );
                    $request->getSession()->getFlashBag()->add('error', 'chercheur.incorrect_password_error');
                }
            }
        }

        return $this->render('home/profile.html.twig', [
            'controller_name' => 'HomeController',
            'locale'          => $request->getLocale(),
            'accountForm'     => $accountForm->createView(),
            'passwordForm'    => $passwordForm->createView(),
            'breadcrumbs'     => [
                ['label' => 'nav.home', 'url' => $this->generateUrl('home')],
                ['label' => 'chercheur.profile']
            ]
        ]);
    }

    /**
     * @Route("/corpus_state", name="corpus_state")
     */
    public function corpusState(Request $request, TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $locale = $request->getLocale();

        $sources = $doctrine
            ->getRepository(Source::class)
            ->findForCorpusState();

        $data = [];

        foreach ($sources as $source) {
            // Check that all attestations have the state required for openAccess
            $states = $source->getAttestations()->map(function ($att) {
                return $att->getEtatFiche()->getOpenAccess();
            })->toArray();
            if (array_unique($states) !== [true]) {
                continue;
            }

            // Get source location. If empty, or no greater region, skip
            $loc = $source->getLieuOrigine() ?? $source->getLieuDecouverte();
            if (is_null($loc)) {
                continue;
            }
            $loc = $loc->getGrandeSousRegion();
            if (!array_key_exists('grandeRegion', $loc) || is_null($loc['grandeRegion'])) {
                continue;
            }

            // Get main bibliographic reference
            $bib = $source->getEditionPrincipaleBiblio();
            if (empty($bib)) {
                continue;
            }
            $bib = $bib->getBiblio();

            // Add entry to data
            if (!array_key_exists($loc['grandeRegion']->getId(), $data)) {
                $data[$loc['grandeRegion']->getId()] = [
                    "label"       => $loc['grandeRegion']->getNom($locale),
                    "progress"    => round($loc['grandeRegion']->getProgression(), 2),
                    "sousRegions" => [],
                    "biblios"     => [],
                ];
            }

            if (!is_null($loc['sousRegion'] ?? null)) {
                if (!array_key_exists($loc['sousRegion']->getId(), $data[$loc['grandeRegion']->getId()]['sousRegions'])) {
                    $data[$loc['grandeRegion']->getId()]['sousRegions'][$loc['sousRegion']->getId()] = [
                        "label"    => $loc['sousRegion']->getNom($locale),
                        "progress" => round($loc['sousRegion']->getProgression(), 2),
                        "biblios"  => []
                    ];
                }
                if (!array_key_exists($bib->getId(), $data[$loc['grandeRegion']->getId()]['sousRegions'][$loc['sousRegion']->getId()]['biblios'])) {
                    $data[$loc['grandeRegion']->getId()]['sousRegions'][$loc['sousRegion']->getId()]['biblios'][$bib->getId()] = [
                        "label" => $bib->getAffichage(),
                        "value" => 0
                    ];
                }
                $data[$loc['grandeRegion']->getId()]['sousRegions'][$loc['sousRegion']->getId()]['biblios'][$bib->getId()]['value']++;
                $sourceIds[] = $source->getId();
            } else {
                if (!array_key_exists($bib->getId(), $data[$loc['grandeRegion']->getId()]['biblios'])) {
                    $data[$loc['grandeRegion']->getId()]['biblios'][$bib->getId()] = [
                        "label" => $bib->getAffichage(),
                        "value" => 0
                    ];
                }
                $data[$loc['grandeRegion']->getId()]['biblios'][$bib->getId()]['value']++;
                $sourceIds[] = $source->getId();
            }
        }

        $tree = [];

        foreach ($data as $r) {
            $tree_r = [
                'text'     => $r['label'],
                'progress' => $r['progress'],
                'icon'     => 'globe-europe',
                'badge'    => 0,
                'children' => []
            ];
            foreach ($r['sousRegions'] as $sr) {
                $tree_sr = [
                    'text'     => $sr['label'],
                    'progress' => $sr['progress'],
                    'icon'     => 'map-marked',
                    'badge'    => 0,
                    'children' => []
                ];

                foreach ($sr['biblios'] as $b_id => $b) {
                    $tree_b = [
                        'text' => $b['label'],
                        'icon' => 'book',
                        'badge' => $b['value'],
                        'link' => $this->generateUrl('bibliography_show', [
                            'id' => $b_id,
                        ])
                    ];
                    $tree_sr['badge'] += $b['value'];
                    $tree_sr['children'][] = $tree_b;
                }

                usort($tree_sr['children'], function ($a, $b) {
                    return $a['text'] <=> $b['text'];
                });

                $tree_r['badge'] += $tree_sr['badge'];
                $tree_r['children'][] = $tree_sr;
            }
            foreach ($r['biblios'] as $b_id => $b) {
                $tree_b = [
                    'text' => $b['label'],
                    'icon' => 'book',
                    'badge' => $b['value'],
                    'link' => $this->generateUrl('bibliography_show', [
                        'id' => $b_id,
                    ])
                ];
                $tree_r['badge'] += $b['value'];
                $tree_r['children'][] = $tree_b;
            }

            usort($tree_r['children'], function ($a, $b) {
                if ($a['icon'] != $b['icon']) {
                    return $b['icon'] <=> $a['icon'];
                }
                return \App\Utils\StringHelper::removeAccents($a['text'])
                    <=> \App\Utils\StringHelper::removeAccents($b['text']);
            });

            $tree[] = $tree_r;
        }

        usort($tree, function ($a, $b) {
            return \App\Utils\StringHelper::removeAccents($a['text'])
                <=> \App\Utils\StringHelper::removeAccents($b['text']);
        });

        return new JsonResponse($tree);
    }

    /**
     * @Route("/help/{locale}/{section}", name="help")
     */
    public function help(string $locale, string $section, Request $request, TranslatorInterface $translator)
    {
        $help_files = [
            'home' => [
                'fr' => 'MAP_Guide_Saisie_FR_accueil.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_homepage.pdf'
            ],
            'source_list' => [
                'fr' => 'MAP_Guide_usager_SOURCE.pdf',
                'en' => 'MAP_User_Guide_SOURCE.pdf'
            ],
            // 'source_list' => [
            //     'fr' => 'MAP_Guide_Saisie_FR_liste_sources.pdf',
            //     'en' => 'MAP_Guidelines_Registration_EN_sources_list.pdf'
            // ],
            'source_form_information' => [
                'fr' => 'MAP_Guide_Saisie_FR_01.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_01.pdf'
            ],
            'source_form_bibliography' => [
                'fr' => 'MAP_Guide_Saisie_FR_02.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_02.pdf'
            ],
            'source_form_datation' => [
                'fr' => 'MAP_Guide_Saisie_FR_03.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_03.pdf'
            ],
            'source_form_location' => [
                'fr' => 'MAP_Guide_Saisie_FR_04.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_04.pdf'
            ],
            'source_form_commentary' => [
                'fr' => 'MAP_Guide_Saisie_FR_05.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_05.pdf'
            ],
            'source_form_testimonies' => [
                'fr' => 'MAP_Guide_Saisie_FR_06.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_06.pdf'
            ],
            'attestation_list' => [
                'fr' => 'MAP_Guide_usager_ATTESTATION.pdf',
                'en' => 'MAP_User_Guide_TESTIMONY.pdf'
            ],
            // 'attestation_list' => [
            //     'fr' => 'MAP_Guide_Saisie_FR_liste_attestations.pdf',
            //     'en' => 'MAP_Guidelines_Registration_EN_testimonies_list.pdf'
            // ],
            'attestation_form_source' => [
                'fr' => 'MAP_Guide_Saisie_FR_07.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_07.pdf'
            ],
            'attestation_form_text' => [
                'fr' => 'MAP_Guide_Saisie_FR_08.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_08.pdf'
            ],
            'attestation_form_context' => [
                'fr' => 'MAP_Guide_Saisie_FR_09.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_09.pdf'
            ],
            'attestation_form_agents' => [
                'fr' => 'MAP_Guide_Saisie_FR_10.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_10.pdf'
            ],
            'attestation_form_datation' => [
                'fr' => 'MAP_Guide_Saisie_FR_11.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_11.pdf'
            ],
            'attestation_form_location' => [
                'fr' => 'MAP_Guide_Saisie_FR_12.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_12.pdf'
            ],
            'attestation_form_commentary' => [
                'fr' => 'MAP_Guide_Saisie_FR_13.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_13.pdf'
            ],
            'attestation_form_elements' => [
                'fr' => 'MAP_Guide_Saisie_FR_14.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_14.pdf'
            ],
            'attestation_form_formulae' => [
                'fr' => 'MAP_Guide_Saisie_FR_15.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_15.pdf'
            ],
            'element_list' => [
                'fr' => 'MAP_Guide_usager_ELEMENT.pdf',
                'en' => 'MAP_User_Guide_ELEMENT.pdf'
            ],
            // 'element_list' => [
            //     'fr' => 'MAP_Guide_Saisie_FR_liste_elements.pdf',
            //     'en' => 'MAP_Guidelines_Registration_EN_elements_list.pdf'
            // ],
            'element_form_description' => [
                'fr' => 'MAP_Guide_Saisie_FR_16.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_16.pdf'
            ],
            'element_form_references' => [
                'fr' => 'MAP_Guide_Saisie_FR_17.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_17.pdf'
            ],
            'element_form_location' => [
                'fr' => 'MAP_Guide_Saisie_FR_18.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_18.pdf'
            ],
            'element_form_bibliography' => [
                'fr' => 'MAP_Guide_Saisie_FR_19.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_19.pdf'
            ],
            'element_form_commentary' => [
                'fr' => 'MAP_Guide_Saisie_FR_20.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_20.pdf'
            ],
            'bibliography_form' => [
                'fr' => 'MAP_Guide_Saisie_FR_21.pdf',
                'en' => 'MAP_Guidelines_Registration_EN_21.pdf'
            ],
            'search' => [
                'fr' => 'MAP_Guide_usager_INTERFACES.pdf',
                'en' => 'MAP_User_Guide_INTERFACES.pdf',
            ],
            'search_simple' => [
                'fr' => 'MAP_Guide_Recherche_simple_FR.pdf',
                'en' => 'MAP_Guidelines_Simple_Research_EN.pdf',
            ],
            'search_guided' => [
                'fr' => 'MAP_Guide_Recherche_guidee_FR.pdf',
                'en' => 'MAP_Guidelines_Guided_Research_EN.pdf',
            ],
            'search_advanced' => [
                'fr' => 'MAP_Guide_Recherche_avancee_FR.pdf',
                'en' => 'MAP_Guidelines_Advanced_Research_EN.pdf',
            ],
            'search_formulae' => [
                'fr' => 'MAP_Guide_Recherche_formule_FR.pdf',
                'en' => 'MAP_Guidelines_Formulae_Research_EN.pdf',
            ],
            'search_sql_mcd' => [
                'fr' => 'MCD_DB_MAP.png',
                'en' => 'MCD_DB_MAP.png',
            ]
        ];

        if (
            !array_key_exists($section, $help_files)
            || !array_key_exists($locale, $help_files[$section])
            || !file_exists($this->getParameter('pdf_path') . '/' . $help_files[$section][$locale])
        ) {
            $request->getSession()->getFlashBag()->add(
                'error',
                $translator->trans('pages.messages.missing_help_file', ['%section%' => $section])
            );
            return $this->redirectToRoute('home');
        }

        return $this->file($this->getParameter('pdf_path') . '/' . $help_files[$section][$locale], null, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
