<?php

namespace Controllers;

use Services\Repositories\UserRepository;

use Services\{Session, CookieHandler};

use Http\Request;

use Http\Responses\{Response, RedirectResponse};

use ResourceNotFoundException;

class DeleteUser implements Controller{
    private $session;
    private $cookieHandler;
    private $userRepository;

    public function __construct(Session $session, CookieHandler $cookieHandler, UserRepository $userRepository){
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request): Response{

        try{
            $user = $this->userRepository->findById($this->session->getSessionProperty('user')['id']);
        }catch(ResourceNotFoundException $e){
            $this->session->logout();
            return new RedirectResponse('?controller=index');
        }

        $curl = \curl_init();

        curl_setopt_array($curl, 
            [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://www.strava.com/oauth/deauthorize',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => [
                    'access_token' => $user->trackingToken()
                ]
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        if($response === false){
            $this->cookieHandler->setCookie('errors', 10, "Dogodila se pogreška, molimo pokušajte malo kasnije.");
            return new RedirectResponse('?controller=userSettings');
        }

        $this->session->logout();
        $this->userRepository->deleteById($user->id());
        return new RedirectResponse('?controller=index');
    }
}