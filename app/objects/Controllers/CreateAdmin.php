<?php

namespace Controllers;

use Http\Responses\{RedirectResponse, Response};
use Services\CookieHandler;
use Services\Repositories\{
    AdminRepository,
    OrganizationRepository
};
use Http\Request;

use Models\Admin;

use ResourceNotFoundException;
use DuplicateEntryException;

/**
 * Creates a new administrator
 */
class CreateAdmin implements Controller{
    private $cookieHandler;
    private $adminRepository;
    private $organizationRepository;

    public function __construct(CookieHandler $cookieHandler, AdminRepository $adminRepository, OrganizationRepository $organizationRepository){
        $this->cookieHandler = $cookieHandler;
        $this->adminRepository = $adminRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function handle(Request $request): Response{
        $username = $request->post()['username'] ?? '';
        $password1 = $request->post()['password1'] ?? '';
        $password2 = $request->post()['password2'] ?? '';
        $organization_id = $request->post()['organization_id'] ?? '';

        \process_passed_parameters($errors, $username, $password1, $password2, $organization_id);

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, ...$errors);
            return new RedirectResponse('?controller=createAdmin');
        }

        \validate_username($username, $errors);
        \validate_passwords($password1, $password2, $errors);

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, ...$errors);
            return new RedirectResponse('?controller=createAdmin');
        }

        try{
            $this->organizationRepository->findById($organization_id);
        }catch(ResourceNotFoundException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Odabrana organizacija ne postoji!');
            return new RedirectResponse('?controller=createAdmin');
        }

        $password = \password_hash($password1, PASSWORD_BCRYPT);
        $admin = new Admin($username, $password, 'admin', $organization_id);

        try{
            $this->adminRepository->persist($admin);
        }catch(DuplicateEntryException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Korisnicko ime vec postoji!');
            return new RedirectResponse('?controller=createAdmin');
        }
        
        $this->cookieHandler->setCookie('messages', 10, 'Administrator uspjesno dodan!');
        return new RedirectResponse('?controller=createAdmin');
    }
}