<?php

namespace AppBundle\Repository;


use Doctrine\DBAL\Connection;

class SearchRepository
{
    /** @var  Connection */
    private $connection;

    /**
     * SearchRepository constructor.
     * @param Connection $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $string
     */
    public function search($string)
    {
        $statement = $this->connection->prepare("SELECT * FROM advertisement WHERE description LIKE :string");
        $statement->bindValue('string','%'.$string.'%');
        $statement->execute();
        return $statement->fetchAll();
    }
}