<?php

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, StatusCodeResponse, RedirectResponse};

use Services\Repositories\{ActivityRepository, UserRepository};

use ResourceNotFoundException;

class StravaWebhook implements Controller{
    private $activityRepository;
    private $userRepository;

    public function __construct(UserRepository $userRepository, ActivityRepository $activityRepository){
        $this->activityRepository = $activityRepository;
        $this->userRepository = $userRepository;
    }

    public function handle(Request $request): Response{
        $body = json_decode($request->raw(), true);

        $aspect_type = $body['aspect_type'] ?? '';
        $event_time = $body['event_time'] ?? '';
        $object_id = $body['object_id'] ?? '';
        $object_type = $body['object_type'] ?? '';
        $owner_id = $body['owner_id'] ?? '';
        $subscription_id = $body['subscription_id'] ?? '';

        \process_passed_parameters($errors, $aspect_type, $event_time, $object_id, $object_type, $owner_id, $subscription_id);

        \file_put_contents('../log.html', 'Process');

        if(empty($errors) === false){
            return new RedirectResponse('?controller=error404');
        }

        if($aspect_type === 'update' && $object_type === 'athlete' && isset($body['updates']['authorized']) && $body['updates']['authorized'] === 'false'){
            // TODO: add what happens when user deauthorizes the app
            return new StatusCodeResponse(200);
        }
        

        if($aspect_type === 'create' && $object_type === 'activity'){
            return $this->createActivity($owner_id, $object_id);
        }

        return new StatusCodeResponse(200);
    }

    private function createActivity($owner_id, $object_id): Response{

        try{
            $user = $this->userRepository->findByTrackingId($owner_id);
        }catch(ResourceNotFoundException $e){
            return new RedirectResponse('?controller=error404');
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

        ob_start();
        var_dump($response);
        \file_put_contents('../log.html', "CREATE" . \ob_get_clean());

        curl_close($curl);

        return new StatusCodeResponse(200);
    }
}