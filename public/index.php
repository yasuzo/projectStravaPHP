<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// set max session lifetime to 30 days
ini_set('session.gc_maxlifetime', 3600*24*30);
// set session cookie lifetime to 30 days
ini_set('session.cookie_lifetime', 3600*24*30);

define('ROOT', __DIR__.'/..');

// strava conf
require_once ROOT.'/app/strava_config.php';

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
    CookieHandler,
    CustomSessionHandler
};

use Routing\Router;

use Services\Repositories\{
    UserRepository,
    AdminRepository,
    OrganizationRepository,
    ActivityRepository,
    WebhookEventRepository,
    SessionRepository
};

use Controllers\{
    IndexOtherController,
    ShowAdminLoginPage,
    PerformAdminLogin,
    ShowAdmins,
    ShowNewAdminForm,
    Error404Controller,
    CreateAdmin,
    DeleteAdmin,
    PerformLogout,
    ShowOrganizations,
    ShowNewOrganizationForm,
    CreateOrganization,
    DeleteOrganization,
    ShowAdminSettings,
    UpdateAdminSettings,
    ShowUserRankingForOrganization,
    ShowMembers,
    UserBanningController,
    ShowOrganizationSettings,
    UpdateOrganizationSettings,
    ShowUserLogin,
    StravaAuth,
    ShowUserIndex,
    ShowUserProfile,
    ShowUserSettings,
    UpdateUserSettings,
    StravaWebhookSubscription,
    StravaWebhook,
    DeleteUser
};

use Http\Responses\HTMLResponse;
use Http\Request;

$userRepository = new UserRepository($db);
$adminRepository = new AdminRepository($db);
$organizationRepository = new OrganizationRepository($db);
$activityRepository = new ActivityRepository($db);
$webhookEventRepository = new WebhookEventRepository($db);
$sessionRepository = new SessionRepository($db);

$templatingEngine = new Templating(ROOT.'/app/views/');
$cookieHandler = new CookieHandler($_COOKIE);

// Create and set custom session handler
$sessionHandler = new CustomSessionHandler($sessionRepository);
session_set_save_handler($sessionHandler);

$session = new Session($sessionRepository);
$firewall = new Firewall($session);
$request = new Request(
    $_SERVER['REQUEST_METHOD'], 
    $_SERVER['HTTP_REFERER'] ?? null, 
    $_GET, 
    $_POST, 
    $_FILES
);

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

$router->addMatch(
    'POST',
    'createAdmin',
    [
        new CreateAdmin($cookieHandler, $adminRepository, $organizationRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'POST',
    'deleteAdmin',
    [
        new DeleteAdmin($session, $adminRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'POST',
    'logout',
    [
        new PerformLogout($session),
        'handle'
    ],
    [
        'superadmin',
        'admin',
        'user'
    ]
);

$router->addMatch(
    'GET',
    'organizations',
    [
        new ShowOrganizations($templatingEngine, $firewall, $organizationRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'GET',
    'createOrganization',
    [
        new ShowNewOrganizationForm($templatingEngine, $firewall, $cookieHandler),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'POST',
    'createOrganization',
    [
        new CreateOrganization($cookieHandler, $organizationRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'POST',
    'deleteOrganization',
    [
        new DeleteOrganization($session, $adminRepository, $organizationRepository),
        'handle'
    ],
    [
        'superadmin'
    ]
);

$router->addMatch(
    'GET',
    'settings',
    [
        new ShowAdminSettings($templatingEngine, $session, $firewall, $cookieHandler, $adminRepository),
        'handle'
    ],
    [
        'superadmin',
        'admin'
    ]
);

$router->addMatch(
    'POST',
    'settings',
    [
        new UpdateAdminSettings($session, $cookieHandler, $adminRepository),
        'handle'
    ],
    [
        'admin',
        'superadmin'
    ]
);

$router->addMatch(
    'GET',
    'index',
    [
        new ShowUserRankingForOrganization($templatingEngine, $session, $firewall, $userRepository, $adminRepository),
        'handle'
    ],
    [
        'admin'
    ]
);

$router->addMatch(
    'GET',
    'members',
    [
        new ShowMembers($templatingEngine, $session, $firewall, $userRepository, $adminRepository),
        'handle'
    ],
    [
        'admin'
    ]
);

// for banning/unbanning users
$router->addMatch(
    'POST',
    'changeUserState',
    [
        new UserBanningController($session, $userRepository, $adminRepository),
        'handle'
    ],
    [
        'admin'
    ]
);

$router->addMatch(
    'GET',
    'organizationSettings',
    [
        new ShowOrganizationSettings($templatingEngine, $session, $firewall, $cookieHandler, $organizationRepository, $adminRepository),
        'handle'
    ],
    [
        'admin'
    ]
);

$router->addMatch(
    'POST',
    'organizationSettings',
    [
        new UpdateOrganizationSettings($session, $cookieHandler, $organizationRepository, $adminRepository),
        'handle'
    ],
    [
        'admin'
    ]
);

$router->addMatch(
    'GET',
    'login',
    [
        new ShowUserLogin($templatingEngine, $session),
        'handle'
    ],
    [
        'other',
        'user'
    ]
);

$stravaAuthController = new StravaAuth($session, $cookieHandler, $userRepository);

// $router->addMatch(
//     'GET',
//     'requestStravaAuth',
//     [
//         $stravaAuthController,
//         'requestAuthorization'
//     ],
//     [
//         'other'
//     ]
// );

$router->addMatch(
    'GET',
    'performStravaAuth',
    [
        $stravaAuthController,
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
        new ShowUserIndex($templatingEngine, $session, $firewall, $userRepository, $organizationRepository),
        'handle'
    ],
    [
        'user'
    ]
);


$router->addMatch(
    'GET',
    'profile',
    [
        new ShowUserProfile($templatingEngine, $session, $firewall, $activityRepository, $organizationRepository, $userRepository),
        'handle'
    ],
    [
        'user'
    ]
);

$router->addMatch(
    'GET',
    'userSettings',
    [
        new ShowUserSettings($templatingEngine, $session, $firewall, $cookieHandler, $userRepository, $organizationRepository),
        'handle'
    ],
    [
        'user'
    ]
);

$router->addMatch(
    'POST',
    'userSettings',
    [
        new UpdateUserSettings($session, $cookieHandler, $userRepository, $organizationRepository),
        'handle'
    ],
    [
        'user'
    ]
);

$router->addMatch(
    'GET',
    'stravaWebhook',
    [
        new StravaWebhookSubscription(),
        'handle'
    ],
    [
        'other'
    ]
);

$router->addMatch(
    'POST',
    'stravaWebhook',
    [
        new StravaWebhook($webhookEventRepository),
        'handle'
    ],
    [
        'other'
    ]
);

$router->addMatch(
    'POST',
    'userDelete',
    [
        new DeleteUser($session, $cookieHandler, $userRepository),
        'handle'
    ],
    [
        'user'
    ]
);

$respose = $router->resolve($request);
$respose->send();

// try{
//     $respose = $router->resolve($request);
//     $respose->send();
// }catch(Throwable $e){
//     http_response_code(500);
//     $response = new HTMLResponse('Ups dogodila se pogreska :( <br>'.$e->getMessage());
//     $response->send();
// }

