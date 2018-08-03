<?php

namespace Controllers;

use Services\{
    Templating,
    Firewall,
    CookieHandler,
    Session
};

use Services\Repositories\AdminRepository;

use Http\Responses\{
    HTMLResponse,
    Response
};

use Http\Request;

/**
 * Shows form for creating a new organization administrator
 */
class ShowAdminSettings implements Controller{
    private $templatingEngine;
    private $firewall;
    private $cookieHandler;
    private $adminRepository;
    private $session;

    public function __construct(Templating $templatingEngine, Session $session, Firewall $firewall, CookieHandler $cookieHandler, AdminRepository $adminRepository){
        $this->templatingEngine = $templatingEngine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->cookieHandler = $cookieHandler;
        $this->adminRepository = $adminRepository;
    }

    public function handle(Request $request): Response{

        // Reads error messages stored in a cookie
        try{
            $usernameErrors = $this->cookieHandler->readCookieArray('usernameErrors');
            $this->cookieHandler->deleteCookie('usernameErrors');
        }catch(\OutOfBoundsException $e){
            $usernameErrors = [];
        }
        try{
            $passwordErrors = $this->cookieHandler->readCookieArray('passwordErrors');
            $this->cookieHandler->deleteCookie('passwordErrors');
        }catch(\OutOfBoundsException $e){
            $passwordErrors = [];
        }

        // Reads success messages stored in a cookie
        try{
            $messages = $this->cookieHandler->readCookieArray('messages');
            $this->cookieHandler->deleteCookie('messages');
        }catch(\OutOfBoundsException $e){
            $messages = [];
        }

        $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);

        $username = $admin->username();

        // Renders content
        $content = $this->templatingEngine->render(
            'layouts/layout_main.php',
            [
                'title' => 'Postavke',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render(
                    'templates/admin_settings_template.php',
                    [
                        'usernameErrors' => $usernameErrors,
                        'passwordErrors' => $passwordErrors,
                        'messages' => $messages,
                        'username' => $username
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}