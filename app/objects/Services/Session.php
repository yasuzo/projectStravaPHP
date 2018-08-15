<?php

namespace Services;

use Services\Repositories\SessionRepository;

class Session{
    private $sessionRepository;
    private $sessionId;

    public function __construct(SessionRepository $sessionRepository){
        $this->sessionRepository = $sessionRepository;

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $this->sessionId = session_id();
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
        $this->sessionRepository->associateWithUser($this->sessionId, $id, $authorizationLevel);
    }

    public function logout($user_id = false, string $user_type = ''): void{
        if($user_id === false){
            $this->sessionRepository->associateWithUser($this->sessionId, null, null);
            unset($_SESSION['user']);
            return;
        }

        $this->sessionRepository->deleteByUserIdAndType($user_id, $user_type);
        
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