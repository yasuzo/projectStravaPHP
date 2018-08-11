<?php

namespace Services\Repositories;

class WebhookEventRepository extends Repository{
    public function __construct(\PDO $db){
        parent::__construct($db);
    }

    public function persist(string $data){
        $query = <<<SQL
        insert into webhook_events
        (data) values (:data);
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':data' => $data]);
    }


    public function findAll(): array{
        $query = <<<SQL
        select * from webhook_events;
SQL;
        $query = $this->db->query($query);
        return $query->fetchAll() ?: [];
    }


    public function deleteById(int $id): void{
        $query = <<<SQL
        delete from webhook_events 
        where id=:id;
SQL;
        $query = $this->db->prepare($query);
        $query->execute([':id' => $id]);
    }
}