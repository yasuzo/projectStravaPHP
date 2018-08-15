<?php

namespace Services\Repositories;

use ResourceNotFoundException;

class SessionRepository extends Repository{
    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    /**
     * Deletes session with given session_id
     *
     * @param string $sessionId
     * @return void
     */
    public function deleteBySessionId(string $sessionId){
        $query = <<<SQL
        delete from sessions where session_id=:session_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':session_id' => $sessionId]);
    }

    /**
     * Deletes session whose owner has given id and and whose type is given as a parameter
     *
     * @param [type] $user_id
     * @param string $type
     * @return void
     */
    public function deleteByUserIdAndType($user_id, string $type){
        $query = <<<SQL
        delete from sessions where user_id=:user_id and type=:type;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':user_id' => $user_id, ':type' => $type]);
    }

    /**
     * Updates session data
     *
     * @param string $sesionId
     * @param string $data
     * @return void
     */
    public function update(string $sessionId, string $data){
        $query = <<<SQL
        update sessions
        set session_data=:session_data 
        where session_id=:session_id; 
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':session_id' => $sessionId,
                ':session_data' => $data
            ]
        );
    }

    /**
     * Creates/updates session data
     *
     * @param string $sessionId
     * @param string $data
     * @return void
     */
    public function persist(string $sessionId, string $data){
        $query = <<<SQL
        insert into sessions 
        (session_id, session_data) values
        (:session_id, :session_data)
        on duplicate key update last_access_time=NOW(), session_data=:session_data;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':session_id' => $sessionId, ':session_data' => $data]);
    }

    /**
     * Returns data column with given session id
     *
     * @param string $sessionId
     * @throws ResourceNotFoundException
     * @return string
     */
    public function readData(string $sessionId): string{
        $query = <<<SQL
        select session_data from sessions where session_id=:session_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':session_id' => $sessionId]);

        if(($data = $query->fetchColumn()) === false){
            throw new ResourceNotFoundException('There is no session with given session id!');
        }

        return $data;
    }

    /**
     * Associates session with a logged user
     *
     * @param string $session_id
     * @param [type] $user_id
     * @param string|null $user_type
     * @return void
     */
    public function associateWithUser(string $session_id, $user_id, ?string $user_type){
        $query = <<<SQL
        update sessions set type=:type, user_id=:user_id
        where session_id=:session_id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':session_id' => $session_id,
                ':type' => $user_type,
                ':user_id' => $user_id
            ]
        );
    }

    /**
     * Deletes rows that have not been accessed for longer than $maxlifetime seconds
     *
     * @param integer $maxlifetime
     * @return void
     */
    public function deleteOlderThan(int $maxlifetime){
        $query = <<<SQL
        delete from sessions 
        where last_access_time < DATE_SUB(NOW(), INTERVAL :maxlifetime SECOND);
SQL;
        $query = $this->db->prepare($query);
        $query->execute(
            [
                ':maxlifetime' => $maxlifetime
            ]
        );
    }
}