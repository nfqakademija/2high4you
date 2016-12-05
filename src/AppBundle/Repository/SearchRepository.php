<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Advertisement;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="search_repository")
 */
class SearchRepository
{
    /**
    * @var  Connection
    */
    private $connection;
    /**
     * @var array
     */
    private $advs = [];
    /**
     * @var array
     */
    private $users = [];

    /**
     * @param $searchString
     * @return array
     */
    private function searchAdvByDesc($searchString)
    {
        $statement = $this->connection->prepare(
            "SELECT * 
             FROM advertisement 
             WHERE description LIKE :searchString AND status = 'enabled'"
        );
        $statement->bindValue('searchString', '%'.$searchString.'%');
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * @param $searchString
     * @return array
     */
    private function searchAdvByDesireDesc($searchString)
    {
        $statement = $this->connection->prepare(
            "SELECT a.id, a.user_id, a.creationDate, a.creationTime, a.theme, a.description, a.status 
             FROM desire d 
             JOIN advertisement a ON a.id = d.adv_id 
             WHERE d.description LIKE :searchString AND a.status = 'enabled'
             GROUP BY a.id, a.user_id, a.creationDate, a.creationTime, a.theme, a.description"
        );
        $statement->bindValue('searchString', '%'.$searchString.'%');
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * @param $searchString
     * @return array
     */

    private function searchAdvByCity($searchString)
    {
        $statement = $this->connection->prepare(
            "SELECT a.id, a.user_id, a.creationDate, a.creationTime, a.theme, a.description, a.status
             FROM advertisement a
             JOIN user u ON a.user_id = u.id
             WHERE u.city LIKE :searchString AND a.status = 'enabled'
             GROUP BY a.id, a.user_id, a.creationDate, a.creationTime, a.theme, a.description"
        );
        $statement->bindValue('searchString', '%'.$searchString.'%');
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * @param integer $advUserId
     * @return array
     */

    private function searchAdvUser($advUserId)
    {
        $statement = $this->connection->prepare(
            "SELECT u.*
             FROM user u
             WHERE u.id = :id"
        );
        $statement->bindValue('id', $advUserId);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * SearchRepository constructor.
     *
     * @param Connection $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    public function setAdvsAndUsers($searchString, $choice)
    {
        if ($choice === 'City') {
            $advs = $this->searchAdvByCity($searchString);
        } elseif ($choice === 'Desire') {
            $advs = $this->searchAdvByDesireDesc($searchString);
        } else {
            $advs = $this->searchAdvByDesc($searchString);
        }
        foreach ($advs as $adv) {
            $a = new Advertisement();
            $a->setId($adv['id']);
            $a->setTheme($adv['theme']);
            $a->getCreationDate($adv['creationDate']);
            $a->getCreationTime($adv['creationTime']);
            $a->setDescription($adv['description']);
            $a->setStatus($adv['status']);
            $u = new User();
            $user = $this->searchAdvUser($adv['user_id'])[0];
            $u->setCity($user['city']);
            $u->setAdverts($a);
            $a->setUser($u);
            $this->advs[] = $a;
            $this->users[] = $u;
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getAdvs()
    {
        return $this->advs;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
