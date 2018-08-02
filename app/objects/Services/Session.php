<?php

namespace Services;

class Session{
    public function __construct(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }

    public function getLoggedUserId(): ?int{
        return $_SESSION['user']['id'] ?? null;
    }

    // TODO: Try to remove it
    public function isAuthenticated(): bool{
        return isset($_SESSION['user']);
    }

    public function authenticate(int $id, string $authorizationLevel): void{
        $_SESSION['user']['id'] = $id;
        $_SESSION['user']['authorizationLevel'] = $authorizationLevel;
    }

    public function logout(): void{
        unset($_SESSION['user']);
    }

    public function destroySessionProperty(string $key): void{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

    public function getSessionProperty(string $key){
        return $_SESSION[$key] ?? null;
    }

    public function setSessionProperty(string $key, $value): void{
        $_SESSION[$key] = $value;
    }
}