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
     * @param $id
     * @throws \ResourceNotFoundException
     * @return Organization
     */
    public function findById($id): Organization{
        $query = <<<SQL
        select id, name, longitude, latitude
        from organizations
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);


        $organization = $query->fetch();

        if($organization === false){
            throw new ResourceNotFoundException('Could not find organization with given id!');
        }
        
        return new Organization(
            $organization['name'],
            new Point($organization['longitude'] / 100000, $organization['latitude'] / 100000),
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
     * @param $id
     * @return void
     */
    public function deleteById($id): void{
        $query = <<<SQL
        delete from organizations
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);
    }
}