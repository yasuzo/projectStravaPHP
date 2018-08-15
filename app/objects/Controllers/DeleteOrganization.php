<?php

namespace Controllers;

use Services\Repositories\{OrganizationRepository, AdminRepository};
use Http\Request;
use Http\Responses\{Response, RedirectResponse};
use Services\Session;

/**
 * Controller designed for superadmin to delete admins
 */
class DeleteOrganization implements Controller{
    private $organizationRepository;
    private $adminRepository;
    private $session;

    public function __construct(Session $session, AdminRepository $adminRepository, OrganizationRepository $organizationRepository){
        $this->organizationRepository = $organizationRepository;
        $this->adminRepository = $adminRepository;
        $this->session = $session;
    }

    public function handle(Request $request): Response{
        $organization_id = $request->post()['organization_id'] ?? '';

        if(is_array($organization_id) === false){
            $organizationAdmins = $this->adminRepository->findFromOrganization($organization_id);
            foreach($organizationAdmins as $admin){
                $this->session->logout($admin['id'], 'admin');
            }
            
            $this->organizationRepository->deleteById($organization_id);
        }

        return new RedirectResponse('?controller=organizations');
    }
}