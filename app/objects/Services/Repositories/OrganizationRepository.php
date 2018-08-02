<?php

namespace Services\Repositories;

use Models\Organization;
use Models\Point;

use ResourceNotFoundException;

class OrganizationRepository extends Repository{

    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    /**
     * Returns an organizations with given id
     *
     * @param integer $id
     * @throws \ResourceNotFoundException
     * @return Organization
     */
    public function findById(int $id): Organization{
        $query = <<<SQL
        select id, name, longitude, latitude
        from organizations
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);

        $organization = $query->fetch();

        if($organization === false){
            throw new ResourceNotFoundException();
        }
        
        return new Organization(
            $organization['name'],
            new Point($organization['longitude'], $organization['latitude']),
            $organization['id']
        );
    } 

    /**
     * Returns all organizations
     *
     * @return array
     */
    public function findAll(): array{
        $query = <<<SQL
        select *
        from organizations
        order by name;
SQL;
        $query = $this->db->query($query);
        return $query->fetchAll() ?: [];
    }

    /**
     * Deletes an organization with the given id from database
     *
     * @param integer $id
     * @return void
     */
    public function deleteById(int $id): void{
        $query = <<<SQL
        delete from organizations
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);
    }
}