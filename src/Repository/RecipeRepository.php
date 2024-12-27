<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function paginateRecipes(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('r'),
            $page,
            3,
            [
                'distinct' => false,
                'sortFiledAllowList' => ['r.title','r.createdAt','r.duration']
            ]
        );
    }

    public function paginateRecipesCustomQuery(QueryBuilder $query, int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $query->getQuery(),
            $page,
            2,
            [
                'distinct' => false,
                'sortFiledAllowList' => ['r.title','r.createdAt','r.duration']
            ]
        );
    }

    public function findTotalDuration(): int{
        return $this->createQueryBuilder("r")
            ->select('SUM(r.duration) as total')
            ->getQuery()
            ->getSingleScalarResult();        
    }

    /**
     * @return Recipe[]
     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder("r")
            ->where("r.duration <= :duration ")
            ->orderBy("r.duration","ASC")
            ->setParameter("duration", $duration)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recipe[]
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder("r")
            ->where("r.categorySlug = :category ")
            ->setParameter("category", $category)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
