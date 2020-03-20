<?php

namespace App\Search;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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

    public function __construct(EntityManagerInterface $em, TagAwareCacheInterface $mapCache)
    {
        $this->em    = $em;
        $this->cache = $mapCache;
    }

    public function getData(string $criteriaName, string $locale): array
    {
        $em = $this->em;
        return $this->cache->get(
            $this->getCacheKey($criteriaName, $locale),
            function (ItemInterface $item) use ($em, $criteriaName, $locale){
                // Compute fully qualified classname from criteria name
                $cls = '\\App\\Search\\Data\\' . ucfirst($criteriaName);

                // If class is not found, return empty array with default lifetime and default tag
                if(!class_exists($cls)){
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
        foreach($criteriaNames as $criteriaName){
            $data[$criteriaName] = $this->getData($criteriaName, $locale);
        }
        return $data;
    }

    public function getDisplay(string $criteriaName, array $values, string $locale): array
    {
        $data = $this->getData($criteriaName, $locale);

        // Reduce array to a single dimension if needed
        // (some criteria will return 2-dimension array to arrange data in optgroups)
        // N.B. : Data will always be either [key => value] or [groupkey => [key => value]]
        if(is_array(array_values($data)[0])){
            $data = array_merge(...array_values($data));
        }

        return array_values(array_filter(
            $data,
            function($id) use ($values) {
                return in_array($id, $values);
            },
            ARRAY_FILTER_USE_KEY
        ));
    }

    private function getCacheKey(string $criteriaName, string $locale): string
    {
        return implode('_', array_map('strtolower', ['search', 'criteria', $criteriaName, $locale]));
    }
}
