<?php

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, RedirectResponse};

use Services\Session;
use Services\Repositories\{AdminRepository, UserRepository};

use ResourceNotFoundException;

class UserBanningController implements Controller{
    private $session;
    private $userRepository;
    private $adminRepository;

    public function __construct(Session $session, UserRepository $userRepository, AdminRepository $adminRepository){
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->adminRespository = $adminRepository;
    }

    public function handle(Request $request): Response{
        $allow = $request->post()['allow'] ?? '';
        $user_id = $request->post()['user_id'] ?? '';

        if(is_array($user_id) === true){
            return new RedirectResponse('?controller=members');
        }

        try{
            $user = $this->userRepository->findById($user_id);
        }catch(ResourceNotFoundException $e){
            return new RedirectResponse('?controller=members');
        }

        $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);

        if($admin->organizationId() !== $user->organizationId()){
            return new RedirectResponse('?controller=members');
        }

        if($allow === 'true'){
            
        }
    }

}