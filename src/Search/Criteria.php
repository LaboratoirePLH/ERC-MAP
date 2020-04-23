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
            if (is_array($value) && is_array($value[0])) {
                $value = array_filter($value, function ($cv) {
                    return array_key_exists('values', $cv)
                        || ((array_key_exists('post_quem', $cv) && !empty($cv['post_quem'])) || (array_key_exists('ante_quem', $cv) && !empty($cv['ante_quem'])))
                        || (array_key_exists('operator', $cv) && array_key_exists('value', $cv) && !!strlen($cv['operator']) && !!strlen($cv['value']));
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
            if ($key === 'new_criteria' || $key === 'search' || $key === 'absoluteForms' || $key === 'queryName') {
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
            if (is_array($value) && !count(array_filter($value))) {
                continue;
            }
            $cleanedCriteria[$key] = array_filter($value, function ($v) {
                return $v !== null && $v !== "";
            });
        }
        if (count(array_intersect(array_keys($cleanedCriteria), ['element_count', 'divine_powers_count', 'formule'])) == 0) {
            return false;
        }
        return $cleanedCriteria;
    }
}
