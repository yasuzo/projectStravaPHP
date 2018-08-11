<?php

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, StatusCodeResponse};

use Services\Repositories\WebhookEventRepository;

use ResourceNotFoundException;

/**
 * Accepts strava webhook events
 */
class StravaWebhook implements Controller{
    private $webhookEventRepository;

    public function __construct(WebhookEventRepository $webhookEventRepository){
        $this->webhookEventRepository = $webhookEventRepository;
    }

    public function handle(Request $request): Response{
        try{
            $this->webhookEventRepository->persist($request->raw());
        }catch(Exception $e){

        }

        return new StatusCodeResponse(200);
    }
}