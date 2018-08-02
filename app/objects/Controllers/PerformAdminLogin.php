<?php

namespace Controllers;

use Http\Responses\{RedirectResponse, Response};
use Services\{Templating, Session, CookieHandler};
use Services\Repositories\AdminRepository;
use Http\Request;

use ResourceNotFoundException;

/**
 * Performs admin login
 */
class PerformAdminLogin implements Controller{
    private $templatingEngine;
    private $session;
    private $cookieHandler;
    private $adminRepository;

    public function __construct(Session $session, CookieHandler $cookieHandler, AdminRepository $adminRepository){
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
        $this->adminRepository = $adminRepository;
    }

    public function handle(Request $request): Response{
        $username = $request->post()['username'] ?? '';
        $password = $request->post()['password'] ?? '';

        \process_passed_parameters($errors, $username, $password);

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, 'Pogrešan unos!');
            return new RedirectResponse('?controller=adminLoginPage');
        } 

        try{
            $admin = $this->adminRepository->findByUsername($username);
            if(\password_verify($password, $admin->password())){
                $this->session->authenticate($admin->id(), $admin->authorizationLevel());
            }else{
                $this->cookieHandler->setCookie('errors', 10, 'Lozinka ili korisničko ime nije ispravno!');
                return new RedirectResponse('?controller=adminLoginPage');
            }
        }catch(ResourceNotFoundException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Lozinka ili korisničko ime nije ispravno!');
            return new RedirectResponse('?controller=adminLoginPage');
        }

        return new RedirectResponse('?controller=index');
    }
}