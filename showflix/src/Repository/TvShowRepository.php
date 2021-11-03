<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TvShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TvShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TvShow[]    findAll()
 * @method TvShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }

    /**
     * Effectue un recherche de série en fonction de la variable $title
     * 
     * Version 1 : Query Builder
     *
     * @param $title
     * @return TvShow[]
     */
    public function searchTvShowByTitle($title) 
    {
        return $this->createQueryBuilder('tvshow')
            // Clause where pour filtrer en fonction de $title
            ->where('tvshow.title LIKE :title')
            ->setParameter(':title', "%$title%")
            ->getQuery()
            ->getResult();
    }
    /**
     * Effectue un recherche de série en fonction de la variable $title
     * 
     * Version 2 : Doctrine Query Language (DQL)
     *
     * @param $title
     * @return TvShow[]
     */
    public function SearchTvShowByTitleDQL($title)
    {
        // Etape 1
        $entityManager = $this->getEntityManager();

        // Etape 2 On prépare la requete de select
        $query = $entityManager->createQuery(
            'SELECT tvshow
            FROM App\Entity\TvShow tvshow
            WHERE title LIKE :title'
        )->setParameter(':title', "%$title%");

        // Etape 3 On retourne le résultat
        return $query->getResult();
    }

    // /**
    //  * @return TvShow[] Returns an array of TvShow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TvShow
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
