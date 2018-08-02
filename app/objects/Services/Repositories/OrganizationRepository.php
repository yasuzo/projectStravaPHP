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
     * Saves organization to the database.
     *
     * @param Organization $organization
     * 
     * @throws \DuplicateEntryException if the organization with given name already exists.
     * 
     * @return void
     */
    public function persist(Organization $organization){
        try{
            $this->findByName($organization->name());
            throw new DuplicateEntryException('Organization with given name already exists!');
        }catch(ResourceNotFoundException $e){
            $query = <<<SQL
            insert into organizations
            (name, longitude, latitude) values
            (:name, :longitude, :latitude);
SQL;
            $query = $this->db->prepare($query);
            $query->execute(
                [
                    ':name' => $organization->name(),
                    ':longitude' => $organization->longitude() * 100000,
                    ':latitude' => $organization->latitude() * 100000
                ]
            );
        }

    }

    /**
     * Returns organization with name passed as parameter.
     *
     * @param string $name
     * 
     * @throws ResourceNotFoundException if organization isn't found.
     * 
     * @return Organization
     */
    public function findByName(string $name): Organization{
        $query = <<<SQL
        select * from organizations
        where name=:name;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':name' => $name]);

        $organization = $query->fetch();

        if($organization === false){
            throw new ResourceNotFoundException('Organization not found!');
        }

        return new Organization(
            $organization['name'],
            new Point($organization['longitude'] / 100000, $organization['latitude'] / 100000),
            $organization['id']
        );
        
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