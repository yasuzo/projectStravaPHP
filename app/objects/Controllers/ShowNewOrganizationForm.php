<?php

namespace Controllers;

use Services\{
    Templating,
    Firewall,
    CookieHandler
};

use Http\Responses\{
    HTMLResponse,
    Response
};

use Http\Request;

/**
 * Shows form for creating a new organization
 */
class ShowNewOrganizationForm implements Controller{
    private $templatingEngine;
    private $firewall;
    private $cookieHandler;

    public function __construct(Templating $templatingEngine, Firewall $firewall, CookieHandler $cookieHandler){
        $this->templatingEngine = $templatingEngine;
        $this->firewall = $firewall;
        $this->cookieHandler = $cookieHandler;
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

        // Renders content
        $content = $this->templatingEngine->render(
            'layouts/layout_main.php',
            [
                'title' => 'Nova organizacija',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render(
                    'templates/create_organization_template.php',
                    [
                        'errors' => $errors,
                        'messages' => $messages
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}