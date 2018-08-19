<?php

namespace Controllers;

use Http\Responses\{RedirectResponse, Response};
use Services\{CookieHandler, Session};
use Services\Repositories\{OrganizationRepository, AdminRepository};
use Http\Request;

use Models\{
    Organization,
    Point
};

use DuplicateEntryException;
use DomainException;
use InvalidArgumentException;

/**
 * pdates organization data
 */
class UpdateOrganizationSettings implements Controller{
    private $session;
    private $cookieHandler;
    private $organizationRepository;
    private $adminRepository;

    public function __construct(Session $session, CookieHandler $cookieHandler, OrganizationRepository $organizationRepository, AdminRepository $adminRepository){
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
        $this->organizationRepository = $organizationRepository;
        $this->adminRepository = $adminRepository;
    }

    public function handle(Request $request): Response{
        $name = $request->post()['organization_name'] ?? '';
        $longitude = $request->post()['lng'] ?? '';
        $latitude = $request->post()['lat'] ?? '';

        $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);

        $organization = $this->organizationRepository->findById($admin->organizationId());

        \process_passed_parameters($errors, $name, $longitude, $latitude);

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, ...$errors);
            return new RedirectResponse('?controller=organizationSettings');
        }

        // see if coordinates are valid
        try{
            $point = new Point($longitude, $latitude);
        }catch(DomainException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Geografska sirina mora biti u intervalu [-90, 90], a duzina u intervalu od [-180, 180].');
            return new RedirectResponse('?controller=organizationSettings');
        }catch(InvalidArgumentException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Geografska sirina i duzina mora biti broj!');
            return new RedirectResponse('?controller=organizationSettings');
        }

        // change data
        $organization->changeName($name);
        $organization->changeCoordinates($point);

        // persist, error expected if organization with the same name already exists
        try{
            $this->organizationRepository->update($organization);
        }catch(DuplicateEntryException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Naziv je vec zauzet!');
            return new RedirectResponse('?controller=organizationSettings');
        }
        
        // success
        $this->cookieHandler->setCookie('messages', 10, 'Postavke su uspjesno spremljene!');
        return new RedirectResponse('?controller=organizationSettings');
    }
}