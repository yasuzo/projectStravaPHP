<?php

namespace Services\Repositories;

abstract class Repository{
    protected $db;

    public function __construct(\PDO $db){
        $this->db = $db;
    }
}

