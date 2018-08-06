<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response, RedirectResponse};
use Services\{Templating, Session};
use Http\Request;

/**
 * Renders user login page
 */
class ShowUserLogin implements Controller{
    private $templatingEngine;
    private $session;

    public function __construct(Templating $engine, Session $session){
        $this->templatingEngine = $engine;
        $this->session = $session;
    }

    public function handle(Request $request): Response{

        if($this->session->isAuthenticated()){
            return new RedirectResponse('?controller=index');
        }

        $content = $this->templatingEngine->render(
            'layouts/layout_login.php', 
            [ 
                'title' => 'Prijava/Registracija',
                'body' => $this->templatingEngine->render('templates/user_login_template.php', [])
            ]
        );

        return new HTMLResponse($content);
    }
}