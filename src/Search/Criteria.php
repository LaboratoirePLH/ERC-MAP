<?php

namespace App\Search;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Criteria
{
    const DEFAULT_CACHE_LIFETIME = 10;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FilesystemAdapter
     */
    private $cache;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TagAwareCacheInterface $mapCache, TranslatorInterface $translator)
    {
        $this->em         = $em;
        $this->cache      = $mapCache;
        $this->translator = $translator;
    }

    public function getData(string $criteriaName, string $locale): array
    {
        $em = $this->em;
        return $this->cache->get(
            $this->getCacheKey($criteriaName, $locale),
            function (ItemInterface $item) use ($em, $criteriaName, $locale) {
                // Compute fully qualified classname from criteria name
                $cls = '\\App\\Search\\Data\\' . ucfirst($criteriaName);

                // If class is not found, return empty array with default lifetime and default tag
                if (!class_exists($cls)) {
                    $item->tag(['Recherche']);
                    $item->expiresAfter(self::DEFAULT_CACHE_LIFETIME);
                    return [];
                }

                // Set Expiration date
                $item->expiresAfter($cls::getCacheLifetime());

                // Set tags : those given by the data class and a global one to reset all cache entries on demand
                $dataTags = $cls::getCacheTags();
                $item->tag(array_merge($dataTags, ['Recherche']));

                // Compute data
                return $cls::compute($em, $locale);
            }
        );
    }

    public function getMultipleData(array $criteriaNames, string $locale): array
    {
        $data = [];
        foreach ($criteriaNames as $criteriaName) {
            $data[$criteriaName] = $this->getData($criteriaName, $locale);
        }
        return $data;
    }

    public function getDisplay(string $criteriaName, array $values, string $locale): array
    {
        if ($criteriaName == 'datation') {
            return array_filter([
                ((!is_null($values['post_quem']) && $values['post_quem'] !== "")
                    ? ($this->translator->trans('datation.fields.post_quem') . ' : ' . $values['post_quem'])
                    : null),
                ((!is_null($values['ante_quem']) && $values['ante_quem'] !== "")
                    ? ($this->translator->trans('datation.fields.ante_quem') . ' : ' . $values['ante_quem'])
                    : null),
                ((($values['exact'] ?? null) === 'datation_exact')
                    ? $this->translator->trans('generic.fields.strict')
                    : null)
            ]);
        }
        if ($criteriaName == "agents") {
            $criteriaName = "agentivities";
        }
        $data = $this->getData($criteriaName, $locale);

        // Reduce array to a single dimension if needed
        // (some criteria will return 2-dimension array to arrange data in optgroups)
        // N.B. : Data will always be either [key => value] or [groupkey => [key => value]]
        if (is_array(array_values($data)[0])) {
            $data = array_merge(...array_values($data));
        }

        return array_values(array_filter(
            $data,
            function ($id) use ($values) {
                return in_array($id, $values);
            },
            ARRAY_FILTER_USE_KEY
        ));
    }

    private function getCacheKey(string $criteriaName, string $locale): string
    {
        return implode('_', array_map('strtolower', ['search', 'criteria', $criteriaName, $locale]));
    }

    public function validateGuidedCriteria(array $criteria)
    {
        $cleanedCriteria = array_filter(
            $criteria,
            function ($value, $key) {
                return in_array(
                    $key,
                    [
                        'names', 'names_mode',
                        'languages', 'languages_mode',
                        'datation', 'locations',
                        'sourceTypes', 'agents'
                    ]
                ) && !empty($value);
            },
            ARRAY_FILTER_USE_BOTH
        );
        if (
            array_key_exists('datation', $cleanedCriteria)
            && $cleanedCriteria['datation']['post_quem'] == ''
            && $cleanedCriteria['datation']['ante_quem'] == ''
        ) {
            unset($cleanedCriteria['datation']);
        }
        if (!count(array_keys($cleanedCriteria))) {
            return false;
        }
        return $cleanedCriteria;
    }

    public function validateAdvancedCriteria(array $criteria)
    {
        $cleanedCriteria = [];
        foreach ($criteria as $key => $value) {
            if ($key === 'new_criteria' || $key === 'search' || $key === 'queryName') {
                continue;
            }
            if ($key === "locationReal") {
                $value = array_unique(array_filter($value));
            }
            if (is_array($value) && is_array($value[0])) {
                $value = array_filter($value, function ($cv) {
                    return array_key_exists('values', $cv)
                        || array_key_exists('value', $cv)
                        || ((array_key_exists('post_quem', $cv) && !is_null($cv['post_quem'])) || (array_key_exists('ante_quem', $cv) && !is_null($cv['ante_quem'])))
                        || (array_key_exists('operator', $cv) && array_key_exists('value', $cv) && !!strlen($cv['operator']) && !!strlen($cv['value']))
                        || (count(array_intersect(['prose', 'poesie'], $cv)));
                });
            }
            if (is_array($value) && !count(array_filter($value))) {
                continue;
            }
            $cleanedCriteria[$key] = $value;
        }
        if (count(array_keys($cleanedCriteria)) <= 1) {
            return false;
        }
        return $cleanedCriteria;
    }

    public function validateElementsCriteria(array $criteria)
    {
        $cleanedCriteria = [];
        foreach ($criteria as $key => $value) {
            if ($key === 'new_criteria' || $key === 'search' || $key === 'names' || $key === 'queryName') {
                continue;
            }
            if (($key === "element_count" || $key === "divine_powers_count") && (($value['operator'] ?? "") === "" || ($value['value'] ?? "") === "")) {
                continue;
            }
            if ($key === "datation" && $value['post_quem'] == '' && $value['ante_quem'] == '') {
                continue;
            }
            if ($key === "languages_mode" && !array_key_exists("languages", $criteria)) {
                continue;
            }
            if ($key === "formules_mode" && !array_key_exists("formule", $criteria)) {
                continue;
            }
            if (is_array($value) && !count(array_filter($value))) {
                continue;
            }
            if ($key === "element_position") {
                $value = array_filter(array_map(function ($v) {
                    if (!is_numeric($v['id'] ?? null)) {
                        return null;
                    }
                    if (!array_key_exists('position', $v) || !in_array($v['position'], ['start', 'end', 'other'])) {
                        $v['position'] = 'any';
                    }
                    return $v;
                }, $value));
                if (count($value) > 0) {
                    $cleanedCriteria[$key] = $value;
                }
            } else {
                $cleanedCriteria[$key] = is_array($value) ? array_filter($value, function ($v) {
                    return $v !== null && $v !== "";
                }) : $value;
            }
        }
        if (count(array_intersect(array_keys($cleanedCriteria), ['element_count', 'divine_powers_count', 'element_position', 'formule'])) == 0) {
            return false;
        }
        return $cleanedCriteria;
    }

    public function getCriteriaDisplay($mode, $criteria, $locale)
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        if ($mode == "simple") {
            return $criteria;
        }
        if ($mode == "guided") {
            $response = [];
            foreach ($criteria as $key => $value) {
                if ($key !== 'names_mode' && $key !== 'languages_mode') {
                    $response['search.criteria_labels.' . $key] = $this->getDisplay($key, $value, $locale);
                }
            }
            if (($criteria['names_mode'] ?? 'one') === 'all'
                && array_key_exists('names', $criteria)
            ) {
                $response['search.criteria_labels.names_all'] = $response['search.criteria_labels.names'];
                unset($response['search.criteria_labels.names']);
            }
            if (($criteria['languages_mode'] ?? 'one') === 'all'
                && array_key_exists('languages', $criteria)
            ) {
                $response['search.criteria_labels.languages_all'] = $response['search.criteria_labels.languages'];
                unset($response['search.criteria_labels.languages']);
            }
            foreach ($response as $key => $value) {
                $response[$this->translator->trans($key)] = $value;
                unset($response[$key]);
            }
            return $response;
        }
        if ($mode == "advanced") {
            $response = [];
            foreach ($criteria as $key => $value) {
                if ($key == "resultsType") {
                    continue;
                }

                // Find translated key
                $criteriaSettings = \App\Search\CriteriaList::getCriteria($key, $this->translator);

                $criteriaLabel = $criteriaSettings['label'];

                switch ($criteriaSettings['type']) {
                    case 'text':
                        $criteriaValues = $value;
                        break;
                    case 'range':
                        $criteriaValues = array_map(function ($v) use ($criteriaSettings) {
                            return \App\Utils\StringHelper::operatorToString($v['operator']) . " " . $criteriaSettings['datalist'][$v['value']];
                        }, $value);
                        break;
                    case 'operation':
                        $criteriaValues = array_map(function ($v) use ($criteriaSettings) {
                            return \App\Utils\StringHelper::operatorToString($v['operator']) . " " . $v['value'];
                        }, $value);
                        break;
                    case 'prosepoetry':
                        $criteriaValues = array_map(function ($v) {
                            return array_map(function ($v_value) {
                                return $this->translator->trans('attestation.fields.' . $v_value);
                            }, $v);
                        }, $value);
                        break;
                    case 'locationreal':
                        $criteriaValues = array_map(function ($v) {
                            return $this->translator->trans('generic.choices.' . $v);
                        }, $value);
                        break;
                    case 'datation':
                        $criteriaValues = array_map(function ($v) use ($key, $locale) {
                            return $this->getDisplay($key, $v, $locale);
                        }, $value);
                        break;
                    case 'select':
                        $criteriaValues = array_map(function ($v) use ($key, $locale) {
                            $v['values'] = $this->getDisplay($key, $v['values'], $locale);
                            $v['mode'] = $v['mode'] ?? 'one';
                            return $v;
                        }, $value);
                        break;
                    default:
                        throw new \InvalidArgumentException("Criteria $key (with value '" . json_encode($value) . "') is not of an accepted type ('{$criteriaSettings['type']}')");
                }

                $response[$criteriaLabel] = $criteriaValues;
            }
            return $response;
        }
        if ($mode == "elements") {
            $response = [];
            foreach ($criteria as $key => $value) {
                switch ($key) {
                    case 'element_count':
                        $response['attestation.sections.elements'] = \App\Utils\StringHelper::operatorToString($value['operator']) . " " . $value['value'];
                        break;
                    case 'divine_powers_count':
                        $response['formule.fields.puissances_divines'] = \App\Utils\StringHelper::operatorToString($value['operator']) . " " . $value['value'];
                        break;
                    case 'formule':
                        $response['search.criteria_labels.formule'] = $value;
                        break;
                    case 'element_position':
                        $response['search.element_labels.position'] = array_map(function ($v) {
                            return "{{$v['id']}} &rarr; " . $this->translator->trans('search.element_labels.position_values.' . $v['position']);
                        }, $value);
                        break;
                    case 'languages_mode':
                    case 'formules_mode':
                        break;
                    default:
                        $response['search.criteria_labels.' . $key] = $this->getDisplay($key, $value, $locale);
                }
            }
            if (($criteria['languages_mode'] ?? 'one') === 'all'
                && array_key_exists('languages', $criteria)
            ) {
                $response['search.criteria_labels.languages_all'] = $response['search.criteria_labels.languages'];
                unset($response['search.criteria_labels.languages']);
            }
            if (($criteria['formules_mode'] ?? 'one') === 'all'
                && array_key_exists('formule', $criteria)
            ) {
                $response['search.criteria_labels.formules_all'] = $response['search.criteria_labels.formule'];
                unset($response['search.criteria_labels.formule']);
            }
            foreach ($response as $key => $value) {
                $response[$this->translator->trans($key)] = $value;
                unset($response[$key]);
            }
            return $response;
        }
    }
}
