<?php

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Templating, Firewall};
use Services\Repositories\OrganizationRepository;
use Http\Request;

/**
 * Lists all organizations
 */
class ShowOrganizations implements Controller{
    private $templatingEngine;
    private $firewall;
    private $organizationRepository;

    public function __construct(Templating $engine, Firewall $firewall, OrganizationRepository $organizationRepository){
        $this->templatingEngine = $engine;
        $this->firewall = $firewall;
        $this->organizationRepository =$organizationRepository;
    }

    public function handle(Request $request): Response{

        $organizations = $this->organizationRepository->findAll();

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Organizacije',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/organizations_template.php', 
                    [
                        'organizations' => $organizations
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}