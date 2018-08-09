<?php

namespace Controllers;

use Http\Request;

use Repositories\ActivityRepository;

class StravaWebhook implements Controller{
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository){
        $this->activityRepository = $activityRepository;
    }

    public function handle(Request $request): Response{
        
    }
}