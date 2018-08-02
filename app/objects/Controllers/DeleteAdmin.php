<?php

namespace Controllers;

use Services\Repositories\AdminRepository;
use Http\Request;
use Http\Responses\{Response, RedirectResponse};

/**
 * Controller designed for superadmin to delete admins
 */
class DeleteAdmin implements Controller{
    private $adminRepository;

    public function __construct(AdminRepository $adminRepository){
        $this->adminRepository = $adminRepository;
    }

    public function handle(Request $request): Response{
        $admin_id = $request->post()['admin_id'] ?? '';

        if(is_array($admin_id) === false){
            $this->adminRepository->deleteById($admin_id);
        }

        return new RedirectResponse('?controller=index');
    }
}