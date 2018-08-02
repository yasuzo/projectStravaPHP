<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Templating, Firewall};
use Services\Repositories\AdminRepository;
use Http\Request;

/**
 * Lists all admins
 */
class ShowAdmins implements Controller{
    private $templatingEngine;
    private $firewall;
    private $adminRepository;

    public function __construct(Templating $engine, Firewall $firewall, AdminRepository $adminRepository){
        $this->templatingEngine = $engine;
        $this->firewall = $firewall;
        $this->adminRepository =$adminRepository;
    }

    public function handle(Request $request): Response{

        $admins = $this->adminRepository->findAdmins();

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Administratori',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/admins_template.php', 
                    [
                        'admins' => $admins
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}