<?php

namespace Services\Repositories;

use Models\User;
use ResourceNotFoundException;

class UserRepository extends Repository{
    
    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    /**
     * Finds user by id
     * 
     * @throws ResourceNotFoundException
     * @param string $id
     * @return User
     */
    public function findById(string $id): User{
        $query = <<<SQL
        select id, firstName, lastName, username, tracking_id, tracking_token, organization_id
        from users
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);

        if(($user = $query->fetch()) === false){
            throw new ResourceNotFoundException();
        }
        $user = new User(
            $user['firstName'], 
            $user['lastName'], 
            $user['username'], 
            $user['tracking_id'], 
            $user['tracking_token'],
            $user['organization_id'], 
            $user['id']
        );
        return $user;
    }

    /**
     * Saves a user to the database
     *
     * @param User $user
     * @return void
     */
    public function persist(User $user): void{
        $query = <<<SQL
        insert into users
        (firstName, lastName, tracking_id, tracking_token) values
        (:firstName, :lastName, :tracking_id, :tracking_token);
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':firstName' => $user->firstName(), 
                ':lastName' => $user->lastName(), 
                ':tracking_id' => $user->trackingId(), 
                ':tracking_token' => $user->trackingToken()
            ]
        );
    }

    /**
     * Deletes a user from the database
     *
     * @param integer $id
     * @return void
     */
    public function deleteById(int $id): void{
        $query = <<<SQL
        delete from users
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);
    }

    /**
     * Updates user's data in the database
     *
     * @param User $user
     * @return void
     */
    public function updateUser(User $user){
        $query = <<<SQL
        update users
        set firstName=:firstName, lastName=:lastName, username=:username, oganization_id=:organization_id
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':firstName' => $user->firstName(), 
                ':lastName' => $user->lastName(), 
                ':username' => $user->username(), 
                ':organization_id' => $user->organizationId(),
                ':id' => $user->id()
            ]
        );
    }

    /**
     * Returns an array of users that are from particular organization
     *
     * @param integer $organization_id
     * @return array
     */
    public function findAllFromOrganization(int $organization_id): array{
        $query = <<<SQL
        select users.id, users.firstName, users.lastName, users.username, case when bans.id is not null
            then 1
            else 0
        end as banned
        from users
        left join bans on users.organization_id=bans.organization_id
        where users.organization_id=:organization_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':organization_id' => $organization_id]);
        
        return $query->fetchAll() ?: [];
    }

}