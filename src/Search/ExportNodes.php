<?php

namespace App\Search;

use App\Entity\Attestation;
use App\Entity\ContientElement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExportNodes
{
    public static function get(array $searchResults, ManagerRegistry $doctrine)
    {
        $attestations = $doctrine->getRepository(Attestation::class)
            ->findBy(['id' => $searchResults]);

        $nodes = [];
        foreach ($attestations as $attestation) {
            /** @var Attestation $attestation */
            foreach ($attestation->getContientElements() as $ce) {
                /** @var ContientElement $ce */
                $el = $ce->getElement();
                if (!array_key_exists($el->getId(), $nodes)) {
                    $nodes[$el->getId()] = [
                        'nodes' => $el->getId(),
                        'id' => $el->getId(),
                        'label' => $el->getEtatAbsolu(),
                        'weight' => 0
                    ];
                }
                $nodes[$el->getId()]['weight'] = $nodes[$el->getId()]['weight'] + 1;
            }
        }
        ksort($nodes);
        return array_values($nodes);
    }
}
