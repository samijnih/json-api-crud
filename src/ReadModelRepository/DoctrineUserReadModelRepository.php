<?php

declare(strict_types=1);

namespace App\ReadModelRepository;

use App\User\GetUser;
use App\User\GetUsers;
use App\User\User;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use RuntimeException;

final class DoctrineUserReadModelRepository implements GetUser, GetUsers
{
    private const TABLE = 'public.user';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(string $id): User
    {
        $result = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE)
            ->where('id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch(FetchMode::ASSOCIATIVE)
        ;

        if (false === $result) {
            throw new RuntimeException("User $id not found.");
        }

        return new User(
            $result['id'],
            $result['first_name'],
            $result['last_name'],
            $this->postgresTzToDateTime($result['created_at']),
            $result['updated_at'] ? $this->postgresTzToDateTime($result['updated_at']) : null,
        );
    }

    private function postgresTzToDateTime($tz): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:sP', $tz);
    }

    public function getAll(int $limit = 15): array
    {
        $results = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(self::TABLE)
            ->setMaxResults($limit)
            ->execute()
            ->fetchAll(FetchMode::ASSOCIATIVE)
        ;

        return array_map(fn (array $row): User => new User(
            $row['id'],
            $row['first_name'],
            $row['last_name'],
            $this->postgresTzToDateTime($row['created_at']),
            $row['updated_at'] ? $this->postgresTzToDateTime($row['updated_at']) : null,
        ), $results);
    }
}
