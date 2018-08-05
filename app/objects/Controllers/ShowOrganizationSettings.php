<?php

namespace Controllers;

use Services\{
    Templating,
    Firewall,
    CookieHandler,
    Session 
};

use Http\Responses\{
    HTMLResponse,
    Response
};

use Services\Repositories\{OrganizationRepository, AdminRepository};

use Http\Request;

/**
 * Shows settings for organization
 */
class ShowOrganizationSettings implements Controller{
    private $templatingEngine;
    private $session;
    private $firewall;
    private $cookieHandler;
    private $organizationRepository;
    private $adminRepository;

    public function __construct(Templating $templatingEngine, Session $session, Firewall $firewall, CookieHandler $cookieHandler, OrganizationRepository $organizationRepository, AdminRepository $adminRepository){
        $this->templatingEngine = $templatingEngine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->cookieHandler = $cookieHandler;
        $this->organizationRepository = $organizationRepository;
        $this->adminRepository = $adminRepository;
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

        $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);

        $organization = $this->organizationRepository->findById($admin->organizationId());

        // Renders content
        $content = $this->templatingEngine->render(
            'layouts/layout_main.php',
            [
                'title' => 'Nova organizacija',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render(
                    'templates/organization_settings_template.php',
                    [
                        'errors' => $errors,
                        'messages' => $messages,
                        'organization' => $organization
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}