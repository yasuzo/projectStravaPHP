<?php

namespace Controllers;

use Services\Repositories\OrganizationRepository;
use Http\Request;
use Http\Responses\{Response, RedirectResponse};

/**
 * Controller designed for superadmin to delete admins
 */
class DeleteOrganization implements Controller{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository){
        $this->organizationRepository = $organizationRepository;
    }

    public function handle(Request $request): Response{
        $organization_id = $request->post()['organization_id'] ?? '';

        if(is_array($organization_id) === false){
            $this->organizationRepository->deleteById($organization_id);
        }

        return new RedirectResponse('?controller=organizations');
    }
}