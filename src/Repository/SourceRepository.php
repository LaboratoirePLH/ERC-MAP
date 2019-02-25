<?php

namespace App\Repository;

use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Source|null find($id, $lockMode = null, $lockVersion = null)
 * @method Source|null findOneBy(array $criteria, array $orderBy = null)
 * @method Source[]    findAll()
 * @method Source[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Source::class);
    }

    /**
     * @return Source[] Returns an array of Source objects
     */
    public function getSimpleList()
    {
        $query = $this->createQueryBuilder('source')
            ->select([
                'partial s.{id, dateCreation, dateModification, version, inSitu, estDatee, traduireFr, traduireEn}',
                'langues',
                'typeSource',
                'categorieSource',
                'createur',
                'dernierEditeur',
                'lieuDecouverte',
                'partial regionDecouverte.{id, nomFr, nomEn}',
                'lieuOrigine',
                'partial regionOrigine.{id, nomFr, nomEn}',
                'datation',
                'partial sourceBiblios.{source, biblio, editionPrincipale, referenceSource}',
                'partial biblio.{id, titreAbrege}'
            ])
            ->from('App\Entity\Source', 's')
            ->leftJoin('s.langues', 'langues')
            ->leftJoin('s.typeSource', 'typeSource')
            ->leftJoin('s.categorieSource', 'categorieSource')
            ->leftJoin('s.createur', 'createur')
            ->leftJoin('s.dernierEditeur', 'dernierEditeur')
            ->leftJoin('s.lieuDecouverte', 'lieuDecouverte')
            ->leftJoin('lieuDecouverte.grandeRegion', 'regionDecouverte')
            ->leftJoin('s.lieuOrigine', 'lieuOrigine')
            ->leftJoin('lieuOrigine.grandeRegion', 'regionOrigine')
            ->leftJoin('s.datation', 'datation')
            ->leftJoin('s.sourceBiblios', 'sourceBiblios', 'WITH', 'sourceBiblios.editionPrincipale = true')
            ->leftJoin('sourceBiblios.biblio', 'biblio')
            ->orderBy('s.dateModification', 'DESC')
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);

        return $query->getResult();
    }

    /**
     * @return Source|array|null Returns a Source object
     */
    public function getRecord(int $id, bool $arrayResult = false)
    {
        $query = $this->createQueryBuilder('source')
            ->select([
                's',
                'typeSource',
                'categorieSource',
                'materiau',
                'categorieMateriau',
                'typeSupport',
                'categorieSupport',
                'auteurs',
                'langues',
                'titrePrincipal',
                'auteurs2',
                'titresCites',
                'auteurs3',
                'datation',
                'sourceBiblios',
                'biblio',
                'corpus',
                'createur',
                'dernierEditeur',
            ])
            ->from('App\Entity\Source', 's')
            ->leftJoin('s.typeSource', 'typeSource')
            ->leftJoin('typeSource.categorieSource', 'categorieSource')
            ->leftJoin('s.materiau', 'materiau')
            ->leftJoin('materiau.categorieMateriau', 'categorieMateriau')
            ->leftJoin('s.typeSupport', 'typeSupport')
            ->leftJoin('typeSupport.categorieSupport', 'categorieSupport')
            ->leftJoin('s.auteurs', 'auteurs')
            ->leftJoin('s.langues', 'langues')
            ->leftJoin('s.titrePrincipal', 'titrePrincipal')
            ->leftJoin('titrePrincipal.auteurs', 'auteurs2')
            ->leftJoin('s.titresCites', 'titresCites')
            ->leftJoin('titresCites.auteurs', 'auteurs3')
            ->leftJoin('s.datation', 'datation')
            ->leftJoin('s.sourceBiblios', 'sourceBiblios')
            ->leftJoin('sourceBiblios.biblio', 'biblio')
            ->leftJoin('biblio.corpus', 'corpus')
            ->leftJoin('s.createur', 'createur')
            ->leftJoin('s.dernierEditeur', 'dernierEditeur')
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);

        if($arrayResult){
            return $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
        return $query->getOneOrNullResult();
    }
}
