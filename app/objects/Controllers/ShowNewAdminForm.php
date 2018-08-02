<?php

namespace Controllers;

use Services\{
    Templating,
    Firewall,
    CookieHandler
};

use Services\Repositories\OrganizationRepository;

use Http\Responses\{
    HTMLResponse,
    Response
};

use Http\Request;

/**
 * Shows form for creating a new organization administrator
 */
class ShowNewAdminForm implements Controller{
    private $templatingEngine;
    private $firewall;
    private $cookieHandler;
    private $organizationRepository;

    public function __construct(Templating $templatingEngine, Firewall $firewall, CookieHandler $cookieHandler, OrganizationRepository $organizationRepository){
        $this->templatingEngine = $templatingEngine;
        $this->firewall = $firewall;
        $this->cookieHandler = $cookieHandler;
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

        $organizations = $this->organizationRepository->findAll();

        // Renders content
        $content = $this->templatingEngine->render(
            'layouts/layout_main.php',
            [
                'title' => 'Novi administrator',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render(
                    'templates/create_admin_template.php',
                    [
                        'errors' => $errors,
                        'messages' => $messages,
                        'organizations' => $organizations
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}