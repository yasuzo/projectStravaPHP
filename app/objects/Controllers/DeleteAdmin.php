<?php

namespace Controllers;

use Services\Session;

use Services\Repositories\AdminRepository;
use Http\Request;
use Http\Responses\{Response, RedirectResponse};

/**
 * Controller designed for superadmin to delete admins
 */
class DeleteAdmin implements Controller{
    private $adminRepository;
    private $session;

    public function __construct(Session $session, AdminRepository $adminRepository){
        $this->adminRepository = $adminRepository;
        $this->session = $session;
    }

    public function handle(Request $request): Response{
        $admin_id = $request->post()['admin_id'] ?? '';

        if(is_array($admin_id) === false){
            $this->adminRepository->deleteById($admin_id);
            $this->session->logout($admin_id, 'admin');
        }

        return new RedirectResponse('?controller=index');
    }
}