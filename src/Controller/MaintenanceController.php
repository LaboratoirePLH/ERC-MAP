<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Utils\StringHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MaintenanceController extends AbstractController
{
    /**
     * @Route("/maintenance", name="maintenance")
     */
    public function index()
    {
        return $this->render('maintenance/index.html.twig', [
            'controller_name' => 'MaintenanceController',
        ]);
    }

    /**
     * @Route("/maintenance/beta_codes", name="maintenance_beta_codes")
     */
    public function betaCodes()
    {
        $query = $this->getDoctrine()->getManager()->createQuery(
            "SELECT partial e.{id, etatAbsolu, betaCode} FROM \App\Entity\Element e"
        );
        $elements = $query->getArrayResult();
        $betaCodes = [];
        foreach ($elements as $el) {
            if (is_null($el['betaCode'])) {
                continue;
            }
            if (preg_match("/[^A-Z, \/\*]/i", $el['betaCode'])) {
                $el['reason'] = 'maintenance.messages.invalid_char';
                $betaCodes[] = $el;
            }

            // Use mb_strlen instead of strlen to compare lengths
            // because strlen("<greek letter>") = 2
            // and mb_strlen("<greek letter>") = 1
            if (
                mb_strlen(StringHelper::removeAccents(strip_tags($el['betaCode'] ?? '')))
                != mb_strlen(StringHelper::removeAccents(strip_tags($el['etatAbsolu'] ?? '')))
            ) {
                $el['reason'] = 'maintenance.messages.invalid_length';
                $betaCodes[] = $el;
            }
        }
        return $this->render('maintenance/index.html.twig', [
            'controller_name' => 'MaintenanceController',
            'beta_codes'      => $betaCodes
        ]);
    }

    /**
     * @Route("/maintenance/formula_numbers", name="maintenance_formula_numbers")
     */
    public function formulaNumbers()
    {
        $query = $this->getDoctrine()->getManager()->createQuery(
            "SELECT partial a.{id}, f FROM \App\Entity\Attestation a INNER JOIN a.formules f ORDER BY a.id ASC, f.positionFormule ASC"
        );
        $attestations = $query->getArrayResult();

        $formulaNumbers = [];
        foreach ($attestations as $a) {
            $idFormules = array_column($a['formules'], 'positionFormule');

            if (count($idFormules)) {
                sort($idFormules);
                $reason = null;
                if (count(array_unique($idFormules)) != count($idFormules)) {
                    $reason = 'maintenance.messages.duplicate_position';
                } else if ($idFormules != range(1, count($idFormules))) {
                    $reason = 'maintenance.messages.missing_position';
                }
                if ($reason !== null) {
                    $formulaNumbers[] = [
                        'id'        => $a['id'],
                        'positions' => $idFormules,
                        'reason'    => $reason
                    ];
                }
            }
        }
        return $this->render('maintenance/index.html.twig', [
            'controller_name' => 'MaintenanceController',
            'formula_numbers'      => $formulaNumbers
        ]);
    }

    /**
     * @Route("/maintenance/html_cleanup", name="maintenance_html_cleanup")
     */
    public function htmlCleanup(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // If request was posted, do the cleaning
        $selection = [];
        $totalSelected = 0;
        $totalUpdated = 0;
        if ($request->isMethod('POST')) {
            $change = $request->request->get('change', []);
            // Process selection
            foreach ($change as $c) {
                list($table, $field, $id) = explode(';', $c);
                $id = json_decode($id, true);
                $selection_key = "$table:$field";
                if (!array_key_exists($selection_key, $selection)) {
                    $selection[$selection_key] = [];
                }
                $selection[$selection_key][] = $id;
                $totalSelected++;
            }
        }

        $functions = [
            "\App\Utils\HTMLCleaner::sanitizeOpenXML"        => 'maintenance.messages.openxml',
            "\App\Utils\HTMLCleaner::sanitizeHtmlEncoding"   => 'maintenance.messages.bad_encoding',
            "\App\Utils\HTMLCleaner::sanitizeHtmlNewLines"   => 'maintenance.messages.too_many_new_lines',
            "\App\Utils\HTMLCleaner::sanitizeHtmlTags"       => 'maintenance.messages.forbidden_tags',
            "\App\Utils\HTMLCleaner::sanitizeHtmlAttributes" => 'maintenance.messages.forbidden_attributes',
            "\App\Utils\StringHelper::normalizeDiacritics"   => 'maintenance.messages.bad_diacritics',
        ];

        $html_fields = [
            'Agent'           => ['id', 'designation', 'commentaireFr', 'commentaireEn'],
            'Attestation'     => ['id', 'extraitAvecRestitution', 'translitteration', 'commentaireFr', 'commentaireEn'],
            'Biblio'          => ['id', 'titreAbrege', 'titreComplet'],
            'ContientElement' => ['id_attestation', 'id_element', 'enContexte'],
            'Datation'        => ['id', 'commentaireFr', 'commentaireEn'],
            'Element'         => ['id', 'etatAbsolu', 'commentaireFr', 'commentaireEn'],
            'Localisation'    => ['id', 'commentaireFr', 'commentaireEn'],
            'Source'          => ['id', 'commentaireFr', 'commentaireEn'],
        ];

        $filter_table = $request->query->get('filter_table', '');
        if ($filter_table !== '') {
            $html_fields = array_filter($html_fields, function ($t) use ($filter_table) {
                return strtolower($t) === strtolower($filter_table);
            }, ARRAY_FILTER_USE_KEY);
        }

        $html_cleanup = [];

        $count_messages = array_fill_keys(array_values($functions), 0);
        $count_tables = array_fill_keys(array_keys($html_fields), 0);

        foreach ($html_fields as $table => $fields) {
            $id_fields = array_filter($fields, function ($f) {
                return substr($f, 0, 2) === "id";
            });
            $non_id_fields = array_diff($fields, $id_fields);

            $query = $em->createQuery("SELECT e FROM \App\Entity\\$table e");

            // Include foreign key primitive values without needing to add joins
            $query->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, 1);


            $results = $query->getArrayResult();
            foreach ($results as $row) {
                foreach ($non_id_fields as $field) {

                    if (strlen($row[$field]) == 0) {
                        continue;
                    }

                    $messages = [];
                    $before = $row[$field];
                    foreach ($functions as $function => $message) {
                        $after = $function($before);
                        if ($after !== $before) {
                            $messages[] = $message;
                        }
                        $before = $after;
                    }


                    if (count($messages)) {
                        if (count($selection) && array_key_exists("$table:$field", $selection)) {
                            foreach ($selection["$table:$field"] as $index => $selection_id) {
                                foreach ($selection_id as $id_field => $id_value) {
                                    if ($row[$id_field] != $id_value) {
                                        // This is not the row we're looking for
                                        continue 2;
                                    }
                                }

                                $id_values = array_values($selection_id);
                                $where = array_map(function ($f) {
                                    return "$f = ?";
                                }, array_keys($selection_id));
                                $where = implode(" AND ", $where);
                                $table_query = \App\Utils\StringHelper::snakeCase($table);
                                $field_query = \App\Utils\StringHelper::snakeCase($field);
                                $update_query = "UPDATE $table_query SET $field_query = ? WHERE $where";
                                $query_params = array_merge([$after], $id_values);
                                $updated = $em->getConnection()->executeUpdate($update_query, $query_params);
                                $totalUpdated += $updated;

                                // We're done, remove this entry from the selection array and skip to the next iteration of the parent foreach (looping on fields)
                                unset($selection["$table:$field"][$index]);
                                continue 2;
                            }
                        }

                        // We only get here if no update was made on the data
                        foreach ($messages as $message) {
                            $count_messages[$message]++;
                        }
                        $count_tables[$table] += count($messages);
                        $html_cleanup[] = [
                            'table' => $table,
                            'field' => $field,
                            'id' => array_filter($row, function ($key) use ($id_fields) {
                                return in_array($key, $id_fields);
                            }, ARRAY_FILTER_USE_KEY),
                            'before' => $row[$field],
                            'after' => $after,
                            'messages' => $messages
                        ];
                    }
                }
            }
        }

        return $this->render('maintenance/html_cleanup.html.twig', [
            'controller_name' => 'MaintenanceController',
            'filter_table'    => $filter_table,
            'total_selected'  => $totalSelected,
            'total_updated'   => $totalUpdated,
            'html_cleanup'    => $html_cleanup,
            'count_messages'  => $count_messages,
            'count_tables'    => $count_tables,
        ]);
    }

    /**
     * @Route("/maintenance/locations_cleanup", name="maintenance_locations_cleanup")
     */
    public function locationsCleanup(Request $request)
    {
        // We are looking for 2 problems :
        //    - Locations that are empty and should be deleted
        //    - Locations that have duplicates (same greaterRegion, subRegion, pleiadesVille, pleiadesSite) and should be merged
        //        WARNING : Merging locations can only be done when fields "Political Entity", "Latitude" and "Longitude" are identical in all duplicates.
        //                  If this is not the case, the script won't allow the merge and will instead ask the user correct those fields (in easyadmin) so the merge can be done automatically
        // For any of those 2 operations to be performed, we need to keep the following informations :
        //    - Operation (merge, delete)
        //    - Localisation ID(s) (1 ID for deletion, 2+ IDs for merge)
        //    - Links :
        //         - Entity Type (Source, Attestation, Agent, Element)
        //         - Entity ID
        //         - Foreign Key field (because Source has 2 location foreign keys)
        // Form is submitted to function below (doLocationsCleanup), then redirected here

        $em = $this->getDoctrine()->getManager();

        // Select all locations
        $allLocations = $em->getRepository(Localisation::class)->findAll();

        // Get all the entities-locations links
        $allLinks = array_merge(
            $em->createQuery("SELECT 'Source' as entity, e.id as id, 'lieuDecouverte' as field, IDENTITY(e.lieuDecouverte) as location_id FROM App\Entity\Source e WHERE e.lieuDecouverte IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT 'Source' as entity, e.id as id, 'lieuOrigine' as field, IDENTITY(e.lieuOrigine) as location_id FROM App\Entity\Source e WHERE e.lieuOrigine IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT 'Attestation' as entity, e.id as id, 'localisation' as field, IDENTITY(e.localisation) as location_id FROM App\Entity\Attestation e WHERE e.localisation IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT 'Agent' as entity, e.id as id, 'localisation' as field, IDENTITY(e.localisation) as location_id FROM App\Entity\Agent e WHERE e.localisation IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT 'Element' as entity, e.id as id, 'localisation' as field, IDENTITY(e.localisation) as location_id FROM App\Entity\Element e WHERE e.localisation IS NOT NULL")->getScalarResult()
        );

        // Separate empty and non-empty locations
        $emptyLocations = array_values(array_filter($allLocations, function ($l) {
            return $l->isBlank();
        }));
        $nonEmptyLocations = array_values(array_diff($allLocations, $emptyLocations));

        // Simplify empty locations to ID and links
        $emptyLocations = array_map(function ($l) use ($allLinks) {
            return [
                'id'    => $l->getId(),
                'links' => array_values(array_filter($allLinks, function ($link) use ($l) {
                    return $link['location_id'] === $l->getId();
                }))
            ];
        }, $emptyLocations);

        // Identify duplicates
        //   - $uniqueLocations is a numeric array containing the "master locations"
        //   - $duplicates is an associative array containing duplicates able to be merged automatically (master location ID as key ; array of locations as value)
        //   - $manualDuplicates is an associative array contaning duplicates requiring manual action (master location ID as key ; array of locations as value)
        $uniqueLocations = [];
        $duplicates = [];
        $manualDuplicates = [];

        function isDuplicate(Localisation $a, Localisation $b): ?bool
        {
            $arr_a = $a->toArray();
            $arr_b = $b->toArray();

            $identicalFields = array_flip(['grandeRegion', 'sousRegion', 'pleiadesVille', 'nomVille', 'pleiadesSite', 'nomSite']);

            $crit_a = array_intersect_key($arr_a, $identicalFields);
            $crit_b = array_intersect_key($arr_b, $identicalFields);
            if ($crit_a === $crit_b) {
                if (
                    $arr_a['entitePolitique'] === $arr_b['entitePolitique']
                    && $arr_a['latitude'] === $arr_b['latitude']
                    && $arr_a['longitude'] === $arr_b['longitude']
                ) {
                    return true;
                } else {
                    return null;
                }
            } else {
                return false;
            }
        }

        foreach ($nonEmptyLocations as $l) {
            $isUnique = true;

            foreach ($uniqueLocations as $ul) {
                // Check if $l is a duplicate of $ul
                $dupe = isDuplicate($l, $ul);
                if ($dupe === false) {
                    continue;
                }
                $targetArray = $dupe ? 'duplicates' : 'manualDuplicates';
                $ul_id = $ul->getId();
                if (!array_key_exists($ul_id, $$targetArray)) {
                    $$targetArray[$ul_id] = [$ul];
                }
                $$targetArray[$ul_id][] = $l;

                // If $l is a duplicate of $ul, but cannot be merged manually, we keep looping on unique locations, because maybe it can be merged directly with another
                if ($dupe) {
                    $isUnique = false;
                    break;
                }
            }
            if ($isUnique) {
                $uniqueLocations[] = $l;
            }
        }
        ksort($duplicates);
        ksort($manualDuplicates);
        // Remove keys, we don't need them anymore
        $duplicates = array_values($duplicates);
        $manualDuplicates = array_values($manualDuplicates);
        // Delete nonEmptyLocations and uniqueLocations, we don't need them anymore
        unset($nonEmptyLocations);
        unset($uniqueLocations);

        // Loop on both arrays (duplicates and manualDuplicates), and prepare view data
        foreach (['duplicates', 'manualDuplicates'] as $arr_name) {
            foreach ($$arr_name as &$values) {
                $first = reset($values);
                $newValues = $first->toArray();
                // Keep only the necessary fields for view display
                $newValues = array_intersect_key($newValues, array_flip(['grandeRegion', 'sousRegion', 'pleiadesVille', 'nomVille', 'pleiadesSite', 'nomSite']));
                // Put locations ID and links in a sub array
                $newValues['links'] = array_reduce($values, function ($links, $value) use ($allLinks) {
                    return array_merge($links, array_values(array_filter($allLinks, function ($link) use ($value) {
                        return $link['location_id'] === $value->getId();
                    })));
                }, []);

                $values = $newValues;
            }
        }

        return $this->render('maintenance/locations_cleanup.html.twig', [
            'controller_name'  => 'MaintenanceController',
            'locale'           => $request->getLocale(),
            'emptyLocations'   => $emptyLocations,
            'duplicates'       => $duplicates,
            'manualDuplicates' => $manualDuplicates
        ]);
    }

    /**
     * @Route("/maintenance/do_locations_cleanup", name="maintenance_do_locations_cleanup")
     */
    public function doLocationsCleanup(Request $request, TranslatorInterface $translator)
    {
        // Operations inner workings :
        //    Delete : - Unlink the given location from every entity to which is was linked (set the foreign key to null).
        //             - Lifecycle events will then automatically remove the orphan location
        //    Merge : - Keep the location with the lowest ID as the master location (could be any other)
        //            - Merge the following fields if they differ between duplicates :
        //                - Commentaire FR & Commentaire EN : Join all unique variants separated by 2 newlines
        //                - Topographies & Fonctions : Merge collections
        //            - Update the foreign key for all entities linked to duplicates other than the master location
        //            - Lifecycle events will then automatically remove the orphan locations

        if ($request->isMethod('POST')) {
            $delete = $request->request->get('delete', []);
            $merge = $request->request->get('merge', []);

            $em = $this->getDoctrine()->getManager();

            $delete = array_reduce($delete, function ($total, $carry) {
                return array_merge($total, json_decode($carry, true));
            }, []);
            $merge = array_reduce($merge, function ($total, $carry) {
                if (count($carry) > 1) {
                    array_push($total, array_map(function ($c) {
                        return json_decode($c, true);
                    }, $carry));
                }
                return $total;
            }, []);

            $total_deleted = 0;
            $total_merged = 0;

            foreach ($delete as $d) {
                // Fetch linked record and remove Localisation
                $record = $em->getRepository("\App\Entity\\" . $d['entity'])->find($d['id']);
                $method = "set" . ucfirst($d['field']);
                $record->$method(null);
            }
            $ids = array_unique(array_column($delete, 'location_id'));
            foreach ($ids as $id) {
                $location = $em->getRepository(Localisation::class)->find($id);
                $em->remove($location);

                $total_deleted++;
            }

            foreach ($merge as $m_group) {
                usort($m_group, function ($a, $b) {
                    return $a['location_id'] <=> $b['location_id'];
                });
                $master = array_shift($m_group);
                $master_location = $em->getRepository(Localisation::class)->find($master['location_id']);
                $commentaireFr = [$master_location->getCommentaireFr()];
                $commentaireEn = [$master_location->getCommentaireEn()];

                foreach ($m_group as $m) {
                    // Update linked record
                    $record = $em->getRepository("\App\Entity\\" . $m['entity'])->find($m['id']);
                    $method = "set" . ucfirst($m['field']);
                    $record->$method($master_location);

                    $to_delete = [];

                    // Fetch location (if it still exists)
                    if (!array_key_exists($m['location_id'], $to_delete)) {
                        $location = $em->getRepository(Localisation::class)->find($m['location_id']);
                        // Get data to merge comments
                        $commentaireFr[] = $location->getCommentaireFr();
                        $commentaireEn[] = $location->getCommentaireEn();

                        // Merge collections
                        foreach ($location->getTopographies() as $t) {
                            $master_location->addTopography($t);
                        }
                        foreach ($location->getFonctions() as $f) {
                            $master_location->addFonction($f);
                        }

                        $total_merged++;
                        $to_delete[$m['location_id']] = $location;
                    }
                }
                foreach ($to_delete as $id => $record) {
                    $em->remove($record);
                }

                // Save comments in master location
                $commentaireFr = array_unique(array_filter(array_map('trim', $commentaireFr)));
                $commentaireEn = array_unique(array_filter(array_map('trim', $commentaireEn)));
                $master_location->setCommentaireFr(implode('<br />', $commentaireFr));
                $master_location->setCommentaireEn(implode('<br />', $commentaireEn));
                $total_merged++;
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add(
                'success',
                $translator->trans('maintenance.messages.locations_cleaned', [
                    '%deleted%' => $total_deleted,
                    '%merged%' => $total_merged
                ])
            );
        }
        return $this->redirectToRoute("maintenance_locations_cleanup");
    }

    /**
     * @Route("/maintenance/is_located_cleanup", name="maintenance_is_located_cleanup")
     */
    public function isLocatedCleanup(Request $request, TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();

        $total_updated = 0;
        $total_updated += $em->createQuery("UPDATE \App\Entity\Agent a SET a.estLocalisee = false WHERE a.estLocalisee = true AND a.localisation IS NULL")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Agent a SET a.estLocalisee = true WHERE a.estLocalisee = false AND a.localisation IS NOT NULL")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Attestation a SET a.estLocalisee = false WHERE a.estLocalisee = true AND a.localisation IS NULL")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Attestation a SET a.estLocalisee = true WHERE a.estLocalisee = false AND a.localisation IS NOT NULL")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Element e SET e.estLocalisee = false WHERE e.estLocalisee = true AND e.localisation IS NULL")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Element e SET e.estLocalisee = true WHERE e.estLocalisee = false AND e.localisation IS NOT NULL")->execute();

        $request->getSession()->getFlashBag()->add(
            'success',
            $translator->trans('maintenance.messages.booleans_cleaned', [
                '%updated%' => $total_updated
            ])
        );
        return $this->redirectToRoute("maintenance_locations_cleanup");
    }

    /**
     * @Route("/maintenance/in_situ_cleanup", name="maintenance_in_situ_cleanup")
     */
    public function inSituCleanup(Request $request, TranslatorInterface $translator)
    {
        $em = $this->getDoctrine()->getManager();

        $total_updated = 0;
        $total_updated += $em->createQuery("UPDATE \App\Entity\Source s SET s.inSitu = false WHERE s.lieuOrigine IS NOT NULL AND s.lieuOrigine != s.lieuDecouverte AND s.inSitu = true")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Source s SET s.inSitu = true WHERE s.lieuOrigine IS NOT NULL AND s.lieuOrigine = s.lieuDecouverte")->execute();
        $total_updated += $em->createQuery("UPDATE \App\Entity\Source s SET s.lieuOrigine = s.lieuDecouverte WHERE s.inSitu = true AND s.lieuOrigine IS NULL")->execute();

        $request->getSession()->getFlashBag()->add(
            'success',
            $translator->trans('maintenance.messages.booleans_cleaned', [
                '%updated%' => $total_updated
            ])
        );
        return $this->redirectToRoute("maintenance_locations_cleanup");
    }
}
