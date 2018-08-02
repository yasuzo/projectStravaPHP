<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Templating, Firewall};
use Http\Request;

/**
 * Renders index template
 * Used when a user isn't logged in
 */
class IndexOtherController implements Controller{
    private $templatingEngine;
    private $firewall;

    public function __construct(Templating $engine, Firewall $firewall){
        $this->templatingEngine = $engine;
        $this->firewall = $firewall;
    }

    public function handle(Request $request): Response{
        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Naslovnica',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/index_other_template.php', [])
            ]
        );

        return new HTMLResponse($content);
    }
}