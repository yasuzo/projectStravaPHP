<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Firewall, Templating};
use Http\Request;

/**
 * Renders 404 
 */
class Error404Controller implements Controller{
    private $templatingEngine;
    private $firewall;

    public function __construct(Templating $engine, Firewall $firewall){
        $this->templatingEngine = $engine;
        $this->firewall = $firewall;
    }

    public function handle(Request $request): Response{
        http_response_code(404);

        $content = $this->templatingEngine->render('layouts/layout_main.php', [
            'title' => '404',
            'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
            'body' => $this->templatingEngine->render('templates/404_template.php', [])
        ]);

        return new HTMLResponse($content);
    }
}