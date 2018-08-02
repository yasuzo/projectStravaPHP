<?php

namespace Services;

use Models\User;

class Firewall{
    private $session;

    public function __construct(Session $session){
        $this->session = $session;
    }

    public function hasAuthorizationLevel(string $level): bool{
        $user = $this->session->getSessionProperty('user');
        if($user == null){
            return false;
        }
        return $user['authorizationLevel'] === $level;
    }

    public function getAuthorizationLevel(): string{
        $user = $this->session->getSessionProperty('user');
        if($user == null){
            return 'other';
        }
        return $user['authorizationLevel'];
    }

}