<?php


namespace Controllers;

use Http\Request;
use Http\Responses\{RedirectResponse, Response};
use Services\Repositories\UserRepository;

use Models\User;

use Services\Session;

use ResourceNotFoundException;

class StravaAuth{
    private $userRepository;
    private $session;

    public function __construct(Session $session, UserRepository $userRepository){
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    public function handle(Request $request): Response{

        $get = $request->get();
        
        $error = $get['error'] ?? '';
        $code = $get['code'] ?? '';

        if($error === 'access_denied' || is_array($code) === true){
            return new RedirectResponse('?controller=login');
        }

        $curl = \curl_init();

        curl_setopt_array($curl, 
            [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://www.strava.com/oauth/token',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => [
                    'client_id' => CLIENT_ID,
                    'client_secret' => CLIENT_SECRET,
                    'code' => $code
                ]
            ]
        );

        $response = json_decode(curl_exec($curl), true);

        if(isset($response['errors']) === true){
            return new RedirectResponse('?controller=error404');
        }

        curl_close($curl);

        $newUser = new User(
            $response['athlete']['firstname'],
            $response['athlete']['lastname'],
            $response['athlete']['id'],
            $response['access_token'],
            $response['athlete']['profile']
        );

        try{
            $user = $this->userRepository->findByTrackingId($newUser->trackingId());

            $user->changeTrackingToken($response['access_token']);

            $this->userRepository->update($user);
        }catch(ResourceNotFoundException $e){
            $this->userRepository->persist($newUser);
            $user = $this->userRepository->findByTrackingId($newUser->trackingId());
        }

        $this->session->authenticate($user->id(), 'user');

        return new RedirectResponse('?controller=index');
    }
}