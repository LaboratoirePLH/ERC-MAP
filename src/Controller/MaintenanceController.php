<?php

namespace App\Controller;

use App\Utils\StringHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
            dump($selection);
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
}
