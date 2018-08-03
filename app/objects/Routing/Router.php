<?php

namespace Routing;

use Http\Request;
use Controllers\Controller;
use Services\Firewall;

class Router{
    private $firewall;
    private $matches;

    public function __construct(Firewall $firewall){
        $this->firewall = $firewall;

        $this->matches = [
            'POST' => [
                'superadmin' => [],
                'admin' => [],
                'user' => [],
                'other' => []
            ],
            'GET' => [
                'superadmin' => [],
                'admin' => [],
                'user' => [],
                'other' => []
            ]
        ];
    }

    /**
     * Finds a callable for requested route.
     * 
     * If the authorization level is 'other' & method is 'GET' & route can not be found under 'other'
     * then the function will search under other authorization levels and if there doesn't find a match
     * it returns 404 page.
     * 
     * For all other possibilities if match can't be found under user's authorization level it immediately
     * returns 404.
     * 
     *
     * @param Request $req
     * @return void
     */
    public function resolve(Request $req){
        $authorizationLevel = $this->firewall->getAuthorizationLevel();
        $controller = $req->get()['controller'] ?? 'index';

        if(\is_array($controller) === true){
            $controller = 'error404';
        }

        $method = $req->method();

        if($method === 'POST' || $authorizationLevel !== 'other'){
            $callable = $this->matches[$method][$authorizationLevel][$controller] ?? $this->matches[$method][$authorizationLevel]['error404'];
            return \call_user_func($callable, $req);
        }

        if($authorizationLevel === 'other' && $method === 'GET'){
            if(isset($this->matches[$method]['other'][$controller]) === true){
                $callable = $this->matches[$method]['other'][$controller];
            }else if(isset($this->matches[$method]['user'][$controller]) === true){
                $callable = $this->matches[$method]['user']['login'];
            // }else if(isset($this->matches[$method]['admin'][$controller]) === true){
            //     $callable = $this->matches[$method]['admin']['adminLoginPage'];
            // }else if(isset($this->matches[$method]['superadmin'][$controller]) === true){
            //     $callable = $this->matches[$method]['superadmin']['adminLoginPage'];
            }else{
                $callable = $this->matches[$method][$authorizationLevel]['error404'];
            }

            return \call_user_func($callable, $req);
        }
    }

    /**
     * Defines a legal route
     *
     * @param string $method
     * @param string $page
     * @param callable $callable
     * @param array $authorizationLevels
     * @return void
     */
    public function addMatch(string $method, string $page, callable $callable, array $authorizationLevels): void{
        foreach ($authorizationLevels as $authorizationLevel) {
            if((array_key_exists($method, $this->matches) && array_key_exists($authorizationLevel, $this->matches['POST'])) === false){
                throw new \OutOfRangeException();
            }
            $this->matches[$method][$authorizationLevel][$page] = $callable;
        }
    }

}