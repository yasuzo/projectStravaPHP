<?php


namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Services\{Templating, Firewall, Session};
use Services\Repositories\{UserRepository, AdminRepository};
use Http\Request;


class ShowUserRankingForOrganization implements Controller{
    private $templatingEngine;
    private $firewall;
    private $userRepository;
    private $session;
    private $adminRepository;

    public function __construct(Templating $engine, Session $session, Firewall $firewall, UserRepository $userRepository, AdminRepository $adminRepository){
        $this->templatingEngine = $engine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
    }

    public function handle(Request $request): Response{

        $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);
        

        $usersByCount = $this->userRepository->findWithCountedActivities($admin->organizationId());
        $usersByDistance = $this->userRepository->findWithActivitiesDistance($admin->organizationId());

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Rang lista organizacije',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/user_ranking_for_organization.php', [
                    'usersByCount' => $usersByCount,
                    'usersByDistance' => $usersByDistance
                ])
            ]
        );

        return new HTMLResponse($content);
    }
}