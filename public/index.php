<?php

define('ROOT', __DIR__.'/..');


// AUTOLOAD
require_once ROOT.'/app/autoload.php';

require_once ROOT.'/app/db_config.php';
require_once ROOT.'/app/baza.php';

require_once ROOT."/app/libraries/helper_functions.php";
require_once ROOT."/app/libraries/validation_helpers.php";

use Services\{
    Session, 
    Templating,
    Firewall,
    CookieHandler
};

use Routing\Router;

use Services\Repositories\{
    UserRepository,
    AdminRepository,
    OrganizationRepository
};

use Controllers\{
    IndexOtherController,
    ShowAdminLoginPage,
    PerformAdminLogin,
    ShowAdmins,
    ShowNewAdminForm,
    Error404Controller
};

use Http\Responses\HTMLResponse;
use Http\Request;

$userRepository = new UserRepository($db);
$adminRepository = new AdminRepository($db);
$organizationRepository = new OrganizationRepository($db);

$templatingEngine = new Templating(ROOT.'/app/views/');

$session = new Session();
$firewall = new Firewall($session);
$request = new Request(
    $_SERVER['REQUEST_METHOD'], 
    $_SERVER['HTTP_REFERER'] ?? null, 
    $_GET, 
    $_POST, 
    $_FILES
);
$cookieHandler = new CookieHandler($_COOKIE);

$router = new Router($firewall);

$router->addMatch(
    'GET',
    'error404',
    [
        new Error404Controller($templatingEngine, $firewall),
        'handle'
    ],
    [
        'other',
        'admin',
        'superadmin',
        'user'
    ]
);

$router->addMatch(
    'POST',
    'error404',
    [
        new Error404Controller($templatingEngine, $firewall),
        'handle'
    ],
    [
        'other',
        'admin',
        'superadmin',
        'user'
    ]
);

$router->addMatch(
    'GET', 
    'index', 
    [
        new IndexOtherController($templatingEngine, $firewall), 
        'handle'
    ],
    [
        'other'
    ]
);

$router->addMatch(
    'GET',
    'adminLoginPage',
    [
        new ShowAdminLoginPage($templatingEngine, $session, $cookieHandler),
        'handle'
    ],
    [
        'other',
        'admin',
        'superadmin'
    ]
);

$router->addMatch(
    'POST',
    'adminLoginPage',
    [
        new PerformAdminLogin($session, $cookieHandler, $adminRepository),
        'handle'
    ],
    [
        'other'
    ]
);

$router->addMatch(
    'GET',
    'index',
    [
        new ShowAdmins($templatingEngine, $firewall, $adminRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'GET',
    'createAdmin',
    [
        new ShowNewAdminForm($templatingEngine, $firewall, $cookieHandler, $organizationRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

try{
    $respose = $router->resolve($request);
    $respose->send();
}catch(Throwable $e){
    http_response_code(500);
    $response = new HTMLResponse('Ups dogodila se pogreska :( '.$e->getMessage());
    $response->send();
}

