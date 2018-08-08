<?php

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, HTMLResponse};

use Services\{
    Templating,
    CookieHandler,
    Session,
    Firewall
};

use Services\Repositories\{
    UserRepository,
    OrganizationRepository
};

use ResourceNotFoundException;

class ShowUserSettings implements Controller{
    private $templatingEngine;
    private $firewall;
    private $cookieHandler;
    private $userRepository;
    private $organizationRepository;
    private $session;

    public function __construct(Templating $templatingEngine, Session $session, Firewall $firewall, CookieHandler $cookieHandler, UserRepository $userRepository, OrganizationRepository $organizationRepository){
        $this->templatingEngine = $templatingEngine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->cookieHandler = $cookieHandler;
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function handle(Request $request): Response{

        // Reads error messages stored in a cookie
        try{
            $errors = $this->cookieHandler->readCookieArray('errors');
            $this->cookieHandler->deleteCookie('errors');
        }catch(\OutOfBoundsException $e){
            $errors = [];
        }

        // Reads success messages stored in a cookie
        try{
            $messages = $this->cookieHandler->readCookieArray('messages');
            $this->cookieHandler->deleteCookie('messages');
        }catch(\OutOfBoundsException $e){
            $messages = [];
        }

        // Find logged user
        $user = $this->userRepository->findById($this->session->getSessionProperty('user')['id']);

        // Find user's organization
        // If user hasn't picked an organization, the organization will be false
        try{
            $chosenOrganization = $this->organizationRepository->findById($user->organizationId());
        }catch(ResourceNotFoundException $e){
            $chosenOrganization = false;
        }

        $organizations = $this->organizationRepository->findAll();

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Postavke',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/user_settings.php', [
                    'errors' => $errors,
                    'messages' => $messages,
                    'user' => $user,
                    'organizations' => $organizations,
                    'chosenOrganization' => $chosenOrganization
                ])
            ]
        );

        return new HTMLResponse($content);

    }

}