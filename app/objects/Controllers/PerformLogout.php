<?php

namespace Controllers;

use Services\Session;

use Http\Request;

use Http\Responses\{Response, RedirectResponse};

class PerformLogout implements Controller{
    private $session;

    public function __construct(Session $session){
        $this->session = $session;
    }

    public function handle(Request $request): Response{
        $this->session->logout();

        return new RedirectResponse('?controller=index');
    }
}