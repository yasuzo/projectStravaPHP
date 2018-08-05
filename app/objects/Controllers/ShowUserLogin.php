<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\Templating;
use Http\Request;

/**
 * Renders user login page
 */
class ShowUserLogin implements Controller{
    private $templatingEngine;
    private $firewall;

    public function __construct(Templating $engine){
        $this->templatingEngine = $engine;
    }

    public function handle(Request $request): Response{
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