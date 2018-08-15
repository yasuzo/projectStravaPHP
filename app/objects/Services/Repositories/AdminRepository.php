<?php

namespace Services\Repositories;

use Models\Admin;
use ResourceNotFoundException;
use DuplicateEntryException;

class AdminRepository extends Repository{

    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    /**
     * Returns admin with id passed as a parameter
     *
     * @param $id
     * @throws ResourceNotFoundException
     * @return Admin
     */
    public function findById($id): Admin{
        $query = <<<SQL
        select id, username, password, organization_id, type
        from admins
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);

        $admin = $query->fetch();

        if($admin === false){
            throw new ResourceNotFoundException("Couldn't find admin with given id!");
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
     * Updates admin in the database
     *
     * @param Admin $admin
     * 
     * @throws \DuplicateEntryException
     * 
     * @return void
     */
    public function update(Admin $admin): void{
        try{
            $temp = $this->findByUsername($admin->username());
            if($admin->id() !== $temp->id()){
                throw new DuplicateEntryException('Username is already taken by other person!');
            }
        }catch(ResourceNotFoundException $e){}
        
        $query = <<<SQL
        update admins
        set username=:username, password=:password, type=:type, organization_id=:organization_id
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':username' => $admin->username(), 
                ':password' => $admin->password(), 
                ':type' => $admin->authorizationLevel(), 
                ':organization_id' => $admin->organizationId(),
                ':id' => $admin->id()
            ]
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
            throw new ResourceNotFoundException('Could not find admin with passed username!');
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
     * @param $id
     * @return void
     */
    public function deleteById($id): void{
        $query = <<<SQL
        delete from admins
        where id=:id and type="admin";
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);
    }

    /**
     * Returns all admins from organization with given id
     *
     * @param [type] $organization_id
     * @return void
     */
    public function findFromOrganization($organization_id){
        $query = <<<SQL
        select *
        from admins
        where organization_id=:organization_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':organization_id' => $organization_id]);
        return $query->fetchAll() ?: [];
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
        select admins.id, admins.username, admins.password, admins.type, organizations.name organization_name
        from admins 
        left join organizations on admins.organization_id = organizations.id
        where admins.type="admin"
        order by organizations.name;
SQL;
        $query = $this->db->query($query);
        return $query->fetchAll() ?: [];
    }

    /**
     * Saves admin to the database
     *
     * @param Admin $admin
     * @throws \DuplicateEntryException if username already exists in the database
     * @return void
     */
    public function persist(Admin $admin): void{
        try{
            $this->findByUsername($admin->username());
            throw new DuplicateEntryException('Admin with given username already exists!');
        }catch(ResourceNotFoundException $e){
            $query = <<<SQL
            insert into admins
            (username, password, type, organization_id) values
            (:username, :password, :type, :organization_id);
SQL;
            $query = $this->db->prepare($query);
            $query->execute([
                ':username' => $admin->username(),
                ':password' => $admin->password(),
                ':type' => $admin->authorizationLevel(),
                ':organization_id' => $admin->organizationId()
            ]);
        }
    }
}