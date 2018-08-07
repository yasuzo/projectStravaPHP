<?php

namespace Services\Repositories;

use Models\Activity;
use ResourceNotFoundException;
use DuplicateEntryException;

class ActivityRepository extends Repository{

    public function __construct(\PDO $db){
        parent::__construct($db);
    }


    /**
     * Returns all activities done by user with id passed as a parameter
     *
     * @param string $user_id
     * @return array
     */
    public function findAllFromUser(string $user_id): array{
        $query = <<<SQL
        select * from activities
        where user_id=:user_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':user_id' => $user_id]);
        return $query->fetchAll() ?: [];
    }

}