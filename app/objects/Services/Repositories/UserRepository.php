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
     * @param $id
     * @return User
     */
    public function findById($id): User{
        $query = <<<SQL
        select id, firstName, lastName, username, tracking_id, tracking_token, organization_id, picture_url
        from users
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);

        if(($user = $query->fetch()) === false){
            throw new ResourceNotFoundException('Could not find user with given id!');
        }
        $user = new User(
            $user['firstName'], 
            $user['lastName'], 
            $user['username'], 
            $user['tracking_id'], 
            $user['tracking_token'],
            $user['picture_url'],
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
        (firstName, lastName, tracking_id, tracking_token, picture_url) values
        (:firstName, :lastName, :tracking_id, :tracking_token, :picture_url);
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':firstName' => $user->firstName(), 
                ':lastName' => $user->lastName(), 
                ':tracking_id' => $user->trackingId(), 
                ':tracking_token' => $user->trackingToken(),
                ':picture_url' => $user->pictureUrl()
            ]
        );
    }

    /**
     * Deletes a user from the database
     *
     * @param $id
     * @return void
     */
    public function deleteById($id): void{
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
     * @param $organization_id
     * @return array
     */
    public function findAllFromOrganization($organization_id): array{
        $query = <<<SQL
        select users.id, users.firstName, users.lastName, users.username, users.picture_url, case when bans.id is not null
            then 1
            else 0
        end as banned
        from users
        left join bans on users.organization_id=bans.organization_id
        where users.organization_id=:organization_id
        order by banned ASC, users.lastName ASC;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':organization_id' => $organization_id]);
        
        return $query->fetchAll() ?: [];
    }

    /**
     * Returns users with counted activities from organization passed as parameter who are not banned
     *
     * @param string $organization_id
     * @return array
     */
    public function findWithCountedActivities(string $organization_id): array{
        $query = <<<SQL
        select users.id, users.firstName, users.lastName, users.username, count(activities.id) count
        from users
        left join bans on users.organization_id=bans.organization_id
        left join activities on users.id=activities.user_id
        where users.organization_id=:organization_id and bans.id is null
        group by users.id
        order by count DESC;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':organization_id' => $organization_id]);
        
        return $query->fetchAll() ?: [];
    }

    /**
     * Returns users with summed activity distances from organization passed as parameter who are not banned
     *
     * @param string $organization_id
     * @return array
     */
    public function findWithActivitiesDistance(string $organization_id): array{
        $query = <<<SQL
        select users.id, users.firstName, users.lastName, users.username, sum(activities.distance) distance
        from users
        left join bans on users.organization_id=bans.organization_id
        left join activities on users.id=activities.user_id
        where users.organization_id=:organization_id and bans.id is null
        group by users.id
        order by distance DESC;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':organization_id' => $organization_id]);
        
        return $query->fetchAll() ?: [];
    }

    /**
     * Bans user from organization if he wasn't already banned.
     *
     * @param string $user_id
     * @param string $organization_id
     * @return void
     */
    public function ban(string $user_id, string $organization_id): void{
        $query = <<<SQL
        select * from bans where user_id=:user_id and organization_id=:organization_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([
            ':user_id' => $user_id,
            ':organization_id' => $organization_id
        ]);

        if($query->fetch() === false){
            return;
        }

        $query = <<<SQL
        insert into bans
        (user_id, organization_id) values
        (:user_id, :organization_id);
SQL;
        $query = $this->db->prepare($query);
        $query->execute([
            ':id' => $id,
            ':organization_id' => $organization_id
        ]);
    }

    /**
     * Removes a ban
     *
     * @param string $user_id
     * @param string $organization_id
     * @return void
     */
    public function removeBan(string $user_id, string $organization_id): void{
        $query = <<<SQL
        delete from bans
        where user_id=:user_id and organization_id=:organization_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':user_id' => $user_id,
                ':organization_id' => $organization_id
            ]
        );
    }
}