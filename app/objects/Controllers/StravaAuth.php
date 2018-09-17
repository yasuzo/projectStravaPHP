<?php


namespace Controllers;

use Http\Request;
use Http\Responses\{RedirectResponse, Response};
use Services\Repositories\UserRepository;

use Models\User;

use Services\{Session, CookieHandler};

use ResourceNotFoundException;

/**
 * User authentication
 */
class StravaAuth{
    private $userRepository;
    private $session;
    private $cookieHandler;

    public function __construct(Session $session, CookieHandler $cookieHandler, UserRepository $userRepository){
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
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

        curl_close($curl);

        // TODO: add error message
        if($response === null){
            return new RedirectResponse('?controller=login');
        }

        if(isset($response['errors']) === true){
            return new RedirectResponse('?controller=error404');
        }

        
        // creates user from received data
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
            // set flag to indicate that the user is new
            $isUserNew = true;

            $this->userRepository->persist($newUser);
            $user = $this->userRepository->findByTrackingId($newUser->trackingId());
        }

        $this->session->authenticate($user->id(), 'user');

        // new users redirects to the settings for username setup and old users to the index page
        if(@$isUserNew === true){
            $this->cookieHandler->setCookie('messages', 10, "Da biste se nalazili na rang listi, molimo odaberite organizaciju i vlastito korisničko ime koje će biti prikazano na rang listi.");
            return new RedirectResponse('?controller=userSettings');
        }else{
            return new RedirectResponse('?controller=index');
        }
        
    }
}