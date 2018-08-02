<?php

namespace Services\Repositories;

use Models\Admin;
use ResourceNotFoundException;

class AdminRepository extends Repository{

    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    /**
     * Returns admin with id passed as a parameter
     *
     * @param integer $id
     * @throws ResourceNotFoundException
     * @return Admin
     */
    public function findById(int $id): Admin{
        $query = <<<SQL
        select id, username, password, organization_id, type
        from admins
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);

        $admin = $query->fetch();

        if($admin === false){
            throw new ResourceNotFoundException();
        }

        return new Admin(
            $admin['username'], 
            $admin['password'], 
            $admin['type'], 
            $admin['organization_id'], 
            $admin['id']
        );
    }

    /**
     * Returns an admin with a username passed to the method
     *
     * @param string $username
     * @throws ResourceNotFoundException
     * @return Admin
     */
    public function findByUsername(string $username): Admin{
        $query = <<<SQL
        select id, username, password, organization_id, type
        from admins
        where username=:username;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':username' => $username]);

        $admin = $query->fetch();

        if($admin === false){
            throw new ResourceNotFoundException();
        }

        return new Admin(
            $admin['username'], 
            $admin['password'], 
            $admin['type'], 
            $admin['organization_id'], 
            $admin['id']
        );
    }

    /**
     * Deletes an admin from a database by id
     * The admin has to be of type 'admin'.
     * Super admins can't be deleted through application
     * 
     * @param integer $id
     * @return void
     */
    public function deleteById(int $id): void{
        $query = <<<SQL
        delete from admins
        where id=:id and type="admin";
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);
    }

    /**
     * Returns all admins of type 'admin'.
     * 
     * NOTE: Return doesn't follow Admin model.
     * 
     * @return array
     */
    public function findAdmins(): array{
        $query = <<<SQL
        select admins.id, admins.password, admins.type, organizations.name organization_name
        from admins 
        left join organizations on admins.organization_id = organizations.id
        where admins.type="admin"
        order by organizations.name;
SQL;
        $query = $this->db->query($query);
        return $query->fetchAll() ?: [];
    }
}