<?php

namespace App\Search;

use App\Entity\Attestation;
use App\Entity\ContientElement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExportResults
{
    public static function getNodes(array $searchResults, ManagerRegistry $doctrine)
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

    public static function getLinks(array $searchResults, ManagerRegistry $doctrine, string $locale)
    {
        $attestations = $doctrine->getRepository(Attestation::class)
            ->findBy(['id' => $searchResults]);

        $links = [];
        foreach ($attestations as $attestation) {
            /** @var Attestation $attestation */
            $ces = $attestation->getContientElements();
            $datation = $attestation->getSource()->getDatation();
            $lieuOrigine = $attestation->getSource()->getLieuOrigine();

            for ($i = 0; $i < count($ces) - 1; $i++) {
                for ($j = $i + 1; $j < count($ces); $j++) {
                    $links[] = [
                        'source'       => min($ces[$i]->getElement()->getId(), $ces[$j]->getElement()->getId()),
                        'target'       => max($ces[$i]->getElement()->getId(), $ces[$j]->getElement()->getId()),
                        'reading'      => $attestation->getFiabiliteAttestation(),
                        'start'        => $datation !== null ? $datation->getPostQuem() : '',
                        'end'          => $datation !== null ? $datation->getAnteQuem() : '',
                        'region'       => ($lieuOrigine !== null && $lieuOrigine->getGrandeRegion() !== null)
                            ? $lieuOrigine->getGrandeRegion()->getNom($locale)
                            : '',
                        'sub_region'   => ($lieuOrigine !== null && $lieuOrigine->getSousRegion() !== null)
                            ? $lieuOrigine->getSousRegion()->getNom($locale)
                            : '',
                        'source_id'    => $attestation->getSource()->getId(),
                        'testimony_id' => $attestation->getId()
                    ];
                }
            }
        }
        usort($links, function ($a, $b) {
            if ($a['source'] != $b['source']) {
                return intval($a['source']) <=> intval($b['source']);
            } else if ($a['target'] != $b['target']) {
                return intval($a['target']) <=> intval($b['target']);
            } else {
                return intval($a['testimony_id']) <=> intval($b['testimony_id']);
            }
        });
        $links = array_values($links);
        foreach ($links as $index => &$link) {
            $link = array_merge(
                ['id' => $index + 1],
                $link
            );
        }
        return $links;
    }
}
