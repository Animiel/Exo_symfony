<?php

namespace App\Repository;

use App\Entity\SessionFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionFormation>
 *
 * @method SessionFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionFormation[]    findAll()
 * @method SessionFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionFormation::class);
    }

    public function save(SessionFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SessionFormation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findNonInscrits($session_id) {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        //sélectionner tous les stagiaires d'une session dont l'id est passé en paramètre
        $qb->select('s')
            ->from('App\Entity\Stagiaire', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();
        //sélectionner tous les stagiaires qui ne sont pas dans le résultat précédent
        //on obtient donc les stagiaires non inscrits pour une session définie
        $sub->select('st')
            ->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            //requête paramétrée
            ->setParameter('id', $session_id)
            //trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.nom');

            //renvoyer le résultat
            $query = $sub->getQuery();
            return $query->getResult();
    }

//    /**
//     * @return SessionFormation[] Returns an array of SessionFormation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SessionFormation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
