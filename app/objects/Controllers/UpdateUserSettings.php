<?php 

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, RedirectResponse};

use Services\{CookieHandler, Session};
use Services\Repositories\{UserRepository, OrganizationRepository};

use DuplicateEntryException;
use ResourceNotFoundException;

class UpdateUserSettings implements Controller{
    private $cookieHandler;
    private $session;
    private $userRepository;
    private $organizationRepository;

    public function __construct(Session $session, CookieHandler $cookieHandler, UserRepository $userRepository, OrganizationRepository $organizationRepository){
        $this->session = $session;
        $this->cookieHandler = $cookieHandler;
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function handle(Request $request): Response{
        $user = $this->userRepository->findById($this->session->getSessionProperty('user')['id']);

        $post = $request->post();

        $firstName = $post['firstName'] ?? '';
        $lastName = $post['lastName'] ?? '';
        $username = $post['username'] ?? '';
        $organization_id = $post['organization_id'] ?? '';

        \process_passed_parameters($errors, $firstName, $lastName, $username, $organization_id);

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, ...$errors);
            return new RedirectResponse('?controller=userSettings');
        }

        $firstName = $firstName;
        $lastName = $lastName;
        \validate_name($firstName, $lastName, $errors);
        \validate_username($username, $errors);

        try{
            $this->organizationRepository->findById($organization_id);
        }catch(ResourceNotFoundException $e){
            $errors[] = 'Odabrana organizacija ne postoji!';
        }

        if(empty($errors) === false){
            $this->cookieHandler->setCookie('errors', 10, ...$errors);
            return new RedirectResponse('?controller=userSettings');
        }

        $user->changeFirstName($firstName);
        $user->changeLastName($lastName);
        $user->changeUsername($username);
        $user->changeOrganizationId($organization_id);

        try{
            $this->userRepository->update($user);
            $this->cookieHandler->setCookie('messages', 10, 'Postavke spremljene!');
        }catch(DuplicateEntryException $e){
            $this->cookieHandler->setCookie('errors', 10, 'Korisnicko ime je zauzeto!');
        }

        return new RedirectResponse('?controller=userSettings');
    }

}