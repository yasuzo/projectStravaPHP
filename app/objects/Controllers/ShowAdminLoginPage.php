<?php

namespace Controllers;

use Services\{
    Templating,
    Session,
    CookieHandler
};

use Http\Responses\{
    HTMLResponse,
    RedirectResponse,
    Response
};

use Http\Request;

/**
 * Renders admin login page
 */
class ShowAdminLoginPage implements Controller{
    private $templatingEngine;
    private $session;
    private $cookieHandler;

    public function __construct(Templating $templatingEngine, Session $session, CookieHandler $cookieHandler){
        $this->templatingEngine = $templatingEngine;
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
    }

    public function handle(Request $request): Response{

        // saves last page a user was on so this can redirect him to that page if he was already logged in
        if($request->httpReferer() !== null && parse_url($request->httpReferer())['host'] === "ciklometar.krizevci.hr"){
            \send_message(parse_url($request->httpReferer())['host']);
            \send_message($request->httpReferer());
            $this->session->setSessionProperty('lastPage', $request->httpReferer());
        }

        // redirects a user if he is already logged in
        if($this->session->isAuthenticated()){
            return new RedirectResponse($this->session->getSessionProperty('lastPage') ?? '?controller=index');
        }

        // Reads error messages stored in a cookie
        try{
            $errors = $this->cookieHandler->readCookieArray('errors');
            $this->cookieHandler->deleteCookie('errors');
        }catch(\OutOfBoundsException $e){
            $errors = [];
        }

        // renders content
        $content = $this->templatingEngine->render(
            'layouts/layout_login.php',
            [
                'title' => 'Admin prijava',
                'body' => $this->templatingEngine->render(
                    'templates/admin_login_template.php',
                    [
                        'errors' => $errors
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}