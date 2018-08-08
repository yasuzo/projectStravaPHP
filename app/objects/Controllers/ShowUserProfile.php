<?php 

namespace Controllers;

use Http\Responses\{HTMLResponse, Response};
use Http\Request;

use Services\{Templating, Session, Firewall};

use Services\Repositories\ActivityRepository;

/**
 * Used to list all logged user's activities
 */
class ShowUserProfile implements Controller{
    private $templatingEngine;
    private $session;
    private $firewall;
    private $activityRepository;

    public function __construct(Templating $templatingEngine, Session $session, Firewall $firewall, ActivityRepository $activityRepository){
        $this->templatingEngine = $templatingEngine;
        $this->session = $session;
        $this->firewall = $firewall;
        $this->activityRepository = $activityRepository;
    }

    public function handle(Request $request): Response{

        $activities = $this->activityRepository->findAllFromUser($this->session->getSessionProperty('user')['id']);

        $content = $this->templatingEngine->render(
            'layouts/layout_main.php', 
            [ 
                'title' => 'Profil',
                'authorizationLevel' => $this->firewall->getAuthorizationLevel(),
                'body' => $this->templatingEngine->render('templates/user_profile.php', 
                    [
                        'activities' => $activities
                    ]
                )
            ]
        );

        return new HTMLResponse($content);
    }
}