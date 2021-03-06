<?php

namespace Services\Repositories;

use Models\{Activity, Point, User};
use ResourceNotFoundException;
use DuplicateEntryException;

class ActivityRepository extends Repository{

    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    /**
     * Returns number of commutes a user has done to the organization since given date
     * 
     * If organization is not selected, this will return 0.
     *
     * @param string $date
     * @param integer $id User's id
     * @return integer
     */
    public function countNewerThanFromUser(string $date, User $user): int{
        $query = <<<SQL
        SELECT count(id) from activities
        where ended_at>:date and user_id=:id and organization_id is not null and organization_id=:organization_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute(['date' => $date, ':id' => $user->id(), ':organization_id' => $user->organizationId()]);
        return $query->fetchColumn() ?: 0;
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
        where user_id=:user_id
        order by ended_at desc;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':user_id' => $user_id]);
        return $query->fetchAll() ?: [];
    }

    /**
     * Returns an activity with given tracking id
     *
     * @param $tracking_id
     * @throws ResourceNotFoundException
     * @return Activity
     */
    public function findByTrackingId(string $tracking_id): Activity{
        $query = <<<SQL
        select id, type, duration, polyline, ROUND(latitude/100000, 5) latitude, ROUND(longitude/100000, 5) longitude, started_at, ended_at, tracking_id, user_id 
        from activities
        where tracking_id=:tracking_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':tracking_id' => $tracking_id]);

        $activity = $query->fetch();

        if($activity === false){
            throw new ResourceNotFoundException('Activity with given tracking id could not be found!');
        }

        return new Activity(
            $activity['type'],
            $activity['distance'],
            $activity['duration'],
            $activity['polyline'],
            new Point($activity['longitude'], $activity['latitude']),
            $activity['started_at'],
            $activity['ended_at'],
            $activity['tracking_id'],
            $activity['user_id'],
            $activity['organization_id'],
            $activity['id']
        );
    }

    /**
     * Saves an activity to the database
     *
     * @param Activity $activity
     * @throws DuplicateEntryException
     * @return void
     */
    public function persist(Activity $activity): void{
        try{
            $this->findByTrackingId($activity->trackingId());
            throw new DuplicateEntryException('Could not persist activity - activity with the same tracking id already exsist!');
        }catch(ResourceNotFoundException $e){
            $query = <<<SQL
            insert into activities
            (type, distance, duration, polyline, latitude, longitude, started_at, ended_at, tracking_id, user_id, organization_id) 
            values
            (:type, :distance, :duration, :polyline, :latitude, :longitude, FROM_UNIXTIME(:started_at), FROM_UNIXTIME(:ended_at), :tracking_id, :user_id, :organization_id);
SQL;
            $query = $this->db->prepare($query);
            $query->execute(
                [
                    ':type' => $activity->type(),
                    ':distance' => $activity->distance(),
                    ':duration' => $activity->duration(),
                    ':polyline' => $activity->polyline(),
                    ':latitude' => $activity->latitude() * 100000,
                    ':longitude' => $activity->longitude() * 100000,
                    ':started_at' => $activity->startedAt(),
                    ':ended_at' => $activity->endedAt(),
                    ':tracking_id' => $activity->trackingId(),
                    ':user_id' => $activity->userId(),
                    ':organization_id' => $activity->organizationId()
                ]
            );
        }
    }

}