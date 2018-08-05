<?php

namespace Controllers;

use Http\Responses\{RedirectResponse, Response};
use Services\CookieHandler;
use Services\Repositories\OrganizationRepository;
use Http\Request;

use Models\{
    Organization,
    Point
};

use DuplicateEntryException;
use DomainException;
use InvalidArgumentException;

/**
 * Creates a new organization
 */
class CreateOrganization implements Controller{
    private $cookieHandler;
    private $organizationRepository;

    public function __construct(CookieHandler $cookieHandler, OrganizationRepository $organizationRepository){
        $this->cookieHandler = $cookieHandler;
        $this->organizationRepository = $organizationRepository;
    }

    public function handle(Request $request): Response{
        $name = $request->post()['organization_name'] ?? '';
        $longitude = $request->post()['lng'] ?? '';
        $latitude = $request->post()['lat'] ?? '';


        \process_passed_parameters($errors, $name, $longitude, $latitude);

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, ...$errors);
            return new RedirectResponse('?controller=createOrganization');
        }

        // see if coordinates are valid
        try{
            $point = new Point($longitude, $latitude);
        }catch(DomainException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Geografska sirina i duzina mora biti u intervalu [-180, 180].');
            return new RedirectResponse('?controller=createOrganization');
        }catch(InvalidArgumentException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Geografska sirina i duzina mora biti broj!');
            return new RedirectResponse('?controller=createOrganization');
        }

        $organization = new Organization($name, $point);

        // persist, error expected if organization with the same name already exists
        try{
            $this->organizationRepository->persist($organization);
        }catch(DuplicateEntryException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Naziv je vec zauzet!');
            return new RedirectResponse('?controller=createOrganization');
        }
        
        // success
        $this->cookieHandler->setCookie('messages', 10, 'Organizacija uspjesno dodana!');
        return new RedirectResponse('?controller=createOrganization');
    }
}