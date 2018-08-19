<?php

define('ROOT', __DIR__ . '/..');

require_once __DIR__ . '/autoload.php';

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/baza.php';

require_once __DIR__ . '/strava_config.php';
require_once __DIR__ . '/libraries/helper_functions.php';


use Services\Repositories\{UserRepository, ActivityRepository, WebhookEventRepository, OrganizationRepository, SessionRepository};
use Models\{Activity, Point};

$userRepository = new UserRepository($db);
$webhookEventRepository = new WebhookEventRepository($db);
$organizationRepository = new OrganizationRepository($db);
$activityRepository = new ActivityRepository($db);
$sessionRepository = new SessionRepository($db);

// creates an activity
function createActivity($owner_id, $object_id){

    global $userRepository, $organizationRepository, $activityRepository;

    try{
        $user = $userRepository->findByTrackingId($owner_id);
    }catch(ResourceNotFoundException $e){
        echo $e->getMessage() . "\n";
        return;
    }

    $curl = \curl_init();

    $headers[] = 'Authorization: Bearer ' . $user->trackingToken();

    curl_setopt_array($curl, 
        [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => 'https://www.strava.com/api/v3/activities/' . $object_id,
        ]
    );

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    if($response === null || isset($response['errors']) === true){
        echo "Response error happened while trying to get details!\n";
        return;
    }

    if($response['end_latlng'] === null){
        echo "There are no end coordinates!\n";
        return;
    }

    try{
        $start_time = strtotime($response['start_date']);
        $end_time = $start_time + $response['elapsed_time'];
        $point = new Point($response['end_latlng'][1], $response['end_latlng'][0]);

        try{
            $org = $organizationRepository->findById($user->organizationId());
        }catch(ResourceNotFoundException $e){
            $org = null;
        }

        // calculate distance from organization if organization is set
        if($org !== null){
            $orgPoint = new Point($org->longitude(), $org->latitude());
            if(Point::distance($point, $orgPoint) < 200){
                echo "Distance from organization is less than 200m!\n";
                $organization_id = $org->id();
            }else{
                echo "Distance from organization is greater than 200m!\n";
                $organization_id = null;
            }
        }else{
            $organization_id = null;
        }

        $activity = new Activity(
            $response['type'], 
            $response['distance'], 
            $response['elapsed_time'], 
            $response['map']['polyline'], 
            $point, 
            $start_time, 
            $end_time, 
            $object_id, 
            $user->id(),
            $organization_id
        );
        
    }catch(Throwable $e){
        echo "ERROR: " . $e->getMessage() . "\n";
        return;
    }

    try{
        $activityRepository->persist($activity);
        echo "Activity with tracking id " . $activity->trackingId() . " successfully saved!\n";
    }catch(Exception $e){
        echo "Can't save document with tracking id " . $activity->trackingId() . ":" . $e->getMessage() . "\n";
    }
    
}

// START
$events = $webhookEventRepository->findAll();

foreach($events as $event){

    $body = json_decode($event['data'], true);

    if($body === null){
        $webhookEventRepository->deleteById($event['id']);
        echo "body === null\n";
        continue;
    }

    $aspect_type = $body['aspect_type'] ?? '';
    $event_time = $body['event_time'] ?? '';
    $object_id = $body['object_id'] ?? '';
    $object_type = $body['object_type'] ?? '';
    $owner_id = $body['owner_id'] ?? '';
    $subscription_id = $body['subscription_id'] ?? '';

    \process_passed_parameters($errors, $aspect_type, $event_time, $object_id, $object_type, $owner_id, $subscription_id);

    if($subscription_id !== SUBSCRIPTION_ID){
        $webhookEventRepository->deleteById($event['id']);
        echo "Invalid subscription id!\n";
        continue;
    }

    if(empty($errors) === false){
        echo "Parameters are not valid!\n";
        $webhookEventRepository->deleteById($event['id']);
        continue;
    }

    if($aspect_type === 'update' && $object_type === 'athlete' && isset($body['updates']['authorized']) && $body['updates']['authorized'] === 'false'){
        echo "User unauthorized an application\n---logging out user---\n";

        try{
            $user = $userRepository->findByTrackingId($owner_id);
        }catch(ResourceNotFoundException $e){
            echo $e->getMessage() . "\n";
            return;
        }

        $sessionRepository->deleteByUserIdAndType($user->id(), 'user');

    }else if($aspect_type === 'create' && $object_type === 'activity'){

        echo "Request to create an activity!\n---creating---\nObject id: " . $object_id . "\n";
        createActivity($owner_id, $object_id);

    }

    $webhookEventRepository->deleteById($event['id']);

    echo "-------------------------------------------------------------------------\n";
    echo "-------------------------------------------------------------------------\n";

}