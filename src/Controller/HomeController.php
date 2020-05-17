<?php

namespace App\Controller;

use App\Entity\Chercheur;
use App\Entity\Source;
use App\Entity\IndexRecherche;
use App\Form\ChercheurType;
use App\Form\ChangePasswordType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $locale = $request->getLocale();

        $counters = $this->getDoctrine()->getRepository(IndexRecherche::class)->getHomeCounters(!$this->isGranted('ROLE_CONTRIBUTOR'));

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user_name'       => $user->getPrenomNom(),
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
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        if ($request->isMethod('POST')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $message = $request->request->get('message', '');
            $subject = $request->request->get('subject', '');

            if (!strlen($message)) {
                $request->getSession()->getFlashBag()->add('error', 'pages.messages.message_mandatory');
                return $this->redirectToRoute('contact');
            }
            if (!strlen($subject)) {
                $request->getSession()->getFlashBag()->add('error', 'pages.messages.subject_mandatory');
                return $this->redirectToRoute('contact');
            }

            $admins = $this->getDoctrine()
                ->getRepository(Chercheur::class)
                ->findBy(["role" => "admin"]);
            $emails = array_map(function ($u) {
                return $u->getMail();
            }, $admins);

            $mail = (new \Swift_Message($translator->trans('mails.contact.title')))
                ->setFrom([$this->fromEmail => $this->fromName])
                ->setTo($emails)
                ->setReplyTo($user->getMail())
                ->setBody(
                    $this->renderView(
                        'email/contact.html.twig',
                        compact('user', 'subject', 'message')
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
    public function language($lang, Request $request)
    {
        if (!in_array($lang, ["fr", "en"])) {
            $request->getSession()->getFlashBag()->add('error', 'generic.messages.invalid_locale');
        } else if ($this->isGranted('ROLE_USER')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->setPreferenceLangue($lang);
            $this->getDoctrine()->getManager()->flush();
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
    public function profile(Request $request, TranslatorInterface $translator)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $accountForm = $this->get('form.factory')->create(ChercheurType::class, $user);
        $accountForm->handleRequest($request);
        $passwordForm = $this->get('form.factory')->create(ChangePasswordType::class, null, [
            'repeat_error' => $translator->trans('chercheur.repeat_password_error')
        ]);
        $passwordForm->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($accountForm->isSubmitted() && $accountForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

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
                    $this->getDoctrine()->getManager()->flush();

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
    public function corpusState(Request $request, TranslatorInterface $translator)
    {
        $locale = $request->getLocale();

        $sources = $this->getDoctrine()
            ->getRepository(Source::class)
            ->findAll();

        $data = [];

        foreach ($sources as $source) {
            // Check that all attestations have the state "Relu/Revised"
            $states = $source->getAttestations()->map(function ($att) {
                return $att->getEtatFiche()->getId();
            })->toArray();
            if (array_unique($states) !== [3]) {
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
                    "sousRegions" => [],
                    "biblios"     => [],
                ];
            }

            if (!is_null($loc['sousRegion'] ?? null)) {
                if (!array_key_exists($loc['sousRegion']->getId(), $data[$loc['grandeRegion']->getId()]['sousRegions'])) {
                    $data[$loc['grandeRegion']->getId()]['sousRegions'][$loc['sousRegion']->getId()] = [
                        "label"    => $loc['sousRegion']->getNom($locale),
                        "biblios" => []
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
                'text' => $r['label'],
                'icon' => 'globe-europe',
                'badge' => 0,
                'children' => []
            ];
            foreach ($r['sousRegions'] as $sr) {
                $tree_sr = [
                    'text' => $sr['label'],
                    'icon' => 'map-marked',
                    'badge' => 0,
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
                return $a['text'] <=> $b['text'];
            });

            $tree[] = $tree_r;
        }

        usort($tree, function ($a, $b) {
            return $a['text'] <=> $b['text'];
        });

        return new JsonResponse($tree);
    }
}
