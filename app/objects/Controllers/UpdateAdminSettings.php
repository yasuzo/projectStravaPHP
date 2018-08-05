<?php 

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, RedirectResponse};

use Services\Repositories\AdminRepository;

use Services\{
    Firewall,
    CookieHandler,
    Session
};

use DuplicateEntryException;

class UpdateAdminSettings implements Controller{
    private $cookieHandler;
    private $adminRepository;
    private $session;

    public function __construct(Session $session, CookieHandler $cookieHandler, AdminRepository $adminRepository){
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
        $this->adminRepository = $adminRepository;
    }

    public function handle(Request $request): Response{
        $update = $request->get()['update'] ?? '';

        if($update !== 'username' && $update !== 'password'){
            return new RedirectResponse('?controller=settings');
        }

        if($update === 'username'){
            $password = $request->post()['password'] ?? '';
            $username = $request->post()['username'] ?? '';

            \process_passed_parameters($errors, $password, $username);
            if(empty($errors) === false){
                $this->cookieHandler->setCookie('usernameErrors', 10, ...$errors);
                return new RedirectResponse('?controller=settings');
            }

            \validate_username($username, $errors);
            if(empty($errors) === false){
                $this->cookieHandler->setCookie('usernameErrors', 10, ...$errors);
                return new RedirectResponse('?controller=settings');
            }

            $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);

            if(\password_verify($password, $admin->password()) === false){
                $this->cookieHandler->setCookie('usernameErrors', 10, 'Lozinka nije ispravna!');
                return new RedirectResponse('?controller=settings');
            }

            $admin->changeUsername($username);

            try{
                $this->adminRepository->update($admin);
                $this->cookieHandler->setCookie('messages', 10, 'Postavke su spremljene!');
            }catch(DuplicateEntryException $e){
                $this->cookieHandler->setCookie('usernameErrors', 10, 'Korisnicko ime je zauzeto!');
                return new RedirectResponse('?controller=settings');
            }
        }else{
            $password = $request->post()['password'] ?? '';
            $newPassword1 = $request->post()['newPassword1'] ?? '';
            $newPassword2 = $request->post()['newPassword2'] ?? '';

            \process_passed_parameters($errors, $password, $newPassword1, $newPassword2);

            if(empty($errors) === false){
                $this->cookieHandler->setCookie('passwordErrors', 10, ...$errors);
                return new RedirectResponse('?controller=settings');
            }

            \validate_passwords($newPassword1, $newPassword2, $errors);
            if(empty($errors) === false){
                $this->cookieHandler->setCookie('passwordErrors', 10, ...$errors);
                return new RedirectResponse('?controller=settings');
            }

            $admin = $this->adminRepository->findById($this->session->getSessionProperty('user')['id']);

            if(\password_verify($password, $admin->password()) === false){
                $this->cookieHandler->setCookie('passwordErrors', 10, 'Lozinka nije ispravna!');
                return new RedirectResponse('?controller=settings');
            }

            $newPassword1 = password_hash($newPassword1, PASSWORD_BCRYPT);
            $admin->changePassword($newPassword1);

            $this->adminRepository->update($admin);
            $this->cookieHandler->setCookie('messages', 10, 'Postavke su spremljene!');
        }

        // success
        return new RedirectResponse('?controller=settings');
    }
}