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
            $stream = fopen("../../incomminng_event_log.txt", 'a');
            fwrite($stream, "\n" . date("d.m.Y, H:i:s") . " .....New event\n");
            
        }catch(Exception $e){
            fwrite($stream, "\n" . date("d.m.Y, H:i:s") . " .....something went wrong\n");
        }finally{
            fclose($stream);
        }

        return new StatusCodeResponse(200);
    }
}