<?php

declare(strict_types=1);

namespace App\ReadModel\Activity;

use App\Entity\UserActivity;
use App\ReadModel\NotFoundException;
use App\ReadModel\Activity\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ActivityFetcher
{
    private $connection;
    private $paginator;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(UserActivity::class);
        $this->paginator = $paginator;
    }

    public function get(string $id): UserActivity
    {
        if (!$userActivity = $this->repository->find($id)) {
            throw new NotFoundException('UserActivity is not found');
        }
        return $userActivity;
    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'action',
                'user',
                'date'
            )
            ->from('user_activity');

        if ($filter->action) {
            $qb->andWhere($qb->expr()->like('LOWER(action)', ':action'));
            $qb->setParameter(':action', '%' . mb_strtolower($filter->action) . '%');
        }

        if ($filter->user) {
            $qb->andWhere($qb->expr()->like('LOWER(user)', ':user'));
            $qb->setParameter(':user', '%' . mb_strtolower($filter->user) . '%');
        }

        if (!\in_array($sort, ['date', 'action', 'user'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }
}