<?php

namespace App\Repository;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Survey>
 *
 * @method Survey|null find($id, $lockMode = null, $lockVersion = null)
 * @method Survey|null findOneBy(array $criteria, array $orderBy = null)
 * @method Survey[]    findAll()
 * @method Survey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survey::class);
    }

    public function save(Survey $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Survey $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Survey[] Returns an array of Survey objects
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

//    public function findOneBySomeField($value): ?Survey
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findActivatedSurvey(): ?Survey
    {
        return $this->createQueryBuilder('s')
            ->where('s.activated = 1')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllByActivated(): ?array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.activated', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function deactivateAllSurveys(): void
    {
        $qb = $this->createQueryBuilder('s');

        $qb->update('App\Entity\Survey', 's')
        ->set('s.activated', ':activated')
        ->setParameter('activated', false)
        ->where('s.activated = :isActivated')
        ->setParameter('isActivated', true)
        ->getQuery()
        ->execute();
    }
}
