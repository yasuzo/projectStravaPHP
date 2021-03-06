<?php

namespace Models;

class Admin {
    private $id;
    private $username;
    private $password;
    private $organization_id;
    private $authorizationLevel;

    public function __construct(string $username, string $password, string $authorizationLevel, $organization_id = null, $id = null){
        $this->username = $username;
        $this->password = $password;
        $this->authorizationLevel = $authorizationLevel;
        $this->organization_id = $organization_id;
        $this->id = $id;
    }

    public function organizationId(){
        return $this->organization_id;
    }

    public function username(): string{
        return $this->username;
    }

    public function changeUsername(string $username): void{
        $this->username = $username;
    }

    public function password(): string{
        return $this->password;
    }

    public function changePassword(string $password): void{
        $this->password = $password;
    }

    public function authorizationLevel(): string{
        return $this->authorizationLevel;
    }

    public function id(){
        return $this->id;
    }
}