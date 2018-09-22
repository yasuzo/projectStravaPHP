<?php 

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Http\Request;

use Services\{Templating, Session, Firewall};

use Services\Repositories\{ActivityRepository, OrganizationRepository, UserRepository};

use ResourceNotFoundException;

/**
 * Used to list all logged user's activities
 */
class ShowUserProfile implements Controller{
    private $templatingEngine;
    private $session;
    private $firewall;
    private $activityRepository;
    private $organizationRepository;
    private $userRepository;

    public function __construct(Templating $templatingEngine, Session $session, Firewall $firewall, ActivityRepository $activityRepository, OrganizationRepository $organizationRepository, UserRepository $userRepository){
        $this->templatingEngine = $templatingEngine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->activityRepository = $activityRepository;
        $this->organizationRepository = $organizationRepository;
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request): Response{

        $activities = $this->activityRepository->findAllFromUser($this->session->getSessionProperty('user')['id']);

        $user = $this->userRepository->findById($this->session->getSessionProperty('user')['id']);

        if($user->organizationId() === null){
            $organization = null;
        }else{
            $organization = $this->organizationRepository->findById($user->organizationId());
        }

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Profil',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/user_profile.php', 
                    [
                        'activities' => $activities,
                        'organization' => $organization
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}