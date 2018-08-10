<?php

namespace Controllers;

use Http\Request;
use Http\Responses\{Response, JSONResponse};

class StravaWebhookSubscription implements Controller{

    public function handle(Request $request): Response{
        ob_start();
        var_dump($request->get());
        \file_put_contents('/log.txt', \ob_get_clean());

        \http_response_code(200);
        return new JSONResponse(['hub.challenge' => $request->get()['hub.challenge'] ?? '']);
    }

}

