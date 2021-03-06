<?php


namespace Controllers;

use Http\Responses\{HTMLResponse, Response, RedirectResponse};
use Services\{Templating, Firewall, Session};
use Services\Repositories\{UserRepository, OrganizationRepository};
use Http\Request;

use ResourceNotFoundException;

/**
 * Used to show ranking of users in organizations
 */
class ShowUserIndex implements Controller{
    private $templatingEngine;
    private $firewall;
    private $userRepository;
    private $session;
    private $organizationRepository;

    public function __construct(Templating $engine, Session $session, Firewall $firewall, UserRepository $userRepository, OrganizationRepository $organizationRepository){
        $this->templatingEngine = $engine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function handle(Request $request): Response{
        $user = $this->userRepository->findById($this->session->getSessionProperty('user')['id']);

        $organizations = $this->organizationRepository->findAll();

        $chosenOrganization_id = $request->get()['organization'] ?? $user->organizationId() ?? false;

        if(is_array($chosenOrganization_id) === true){
            return new RedirectResponse('?controller=index');
        }

        if(empty($organizations) === true){
            $chosenOrganization = false;
        }else{
            // User is not a member of an organization and organization is not given in GET
            if($chosenOrganization_id === false){
                $chosenOrganization_id = $organizations[0]['id'];
            }

            try{
                $chosenOrganization = $this->organizationRepository->findById($chosenOrganization_id);

                $usersByCount = $this->userRepository->findWithCountedActivities($chosenOrganization->id());
                $usersByDistance = $this->userRepository->findWithActivitiesDistance($chosenOrganization->id());
            }catch(ResourceNotFoundException $e){
                return new RedirectResponse('?controller=index');
            }
        }
        

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Rang lista',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/user_ranking.php', [
                    'usersByCount' => $usersByCount ?? [],
                    'usersByDistance' => $usersByDistance ?? [],
                    'chosenOrganization' => $chosenOrganization,
                    'organizations' => $organizations,
                    'user' => $user
                ])
            ]
        );

        return new HTMLResponse($content);
    }
}