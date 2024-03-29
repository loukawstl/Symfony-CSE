<?php

namespace App\Repository;

use App\Entity\Partnership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partnership>
 *
 * @method Partnership|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partnership|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partnership[]    findAll()
 * @method Partnership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnershipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partnership::class);
    }

    public function save(Partnership $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Partnership $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Partnership[] Returns an array of Partnership objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Partnership
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findRandomPartnerships(int $nb): array {

        $queryResult = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) AS nbPartnerships')
            ->getQuery()
            ->getResult()
        ;

        $nbPartnerships = $queryResult[0]["nbPartnerships"];
        $i = 0;
        $partnerships = [];
        $partnershipIds = [];

        while($i < $nb){
        //nb = nombre de parteriats retournés voulu. Le if est là au cas ou il n'y a pas assez de partenariats dans la BDD.
            if ($i < $nbPartnerships){
                $randomOffset = rand(0, $nbPartnerships - (1 + $i));

                $queryBuilder = $this->createQueryBuilder('p');
                if ($i !== 0){
                    $queryBuilder->where($queryBuilder->expr()->notIn('p.id', $partnershipIds));
                }
                //retourne un partenariat avec un offset entre 0 et le nombre de partenariats - 1 + le nombre de partenariats déjà retournés
                $queryBuilder->setMaxResults(1)->setFirstResult($randomOffset);

                $partnership = $queryBuilder->getQuery()->getOneOrNullResult();
                $partnerships[] = $partnership;
                //l'id des partenariats sont stockés dans une liste différente afin de les utiliser dans le where
                $partnershipIds[] = $partnership->getId();
            }
            $i++;
        }

        return $partnerships;
    }

}
