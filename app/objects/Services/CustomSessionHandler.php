<?php

namespace Services;

use Services\Repositories\SessionRepository;

use ResourceNotFoundException;

class CustomSessionHandler implements \SessionHandlerInterface{
    private $sessionRepository;

    public function __construct(SessionRepository $sessionRepository){
        $this->sessionRepository = $sessionRepository;
    }

    public function destroy($sessionId){
        $this->sessionRepository->deleteBySessionId($sessionId);

        return true;
    }

    public function gc($lifetime){
        $this->sessionRepository->deleteOlderThan($lifetime);

        return true;
    }

    public function open($save_path, $name){

        return true;
    }

    public function read($sessionId){
        try{
            $data = $this->sessionRepository->readData($sessionId);
        }catch(ResourceNotFoundException $e){
            $data = '';
        }

        return $data;
    }

    public function write($sessionId, $session_data){
        $this->sessionRepository->persist($sessionId, $session_data);

        return true;
    }

    public function close(){
        
        return true;
    }

}