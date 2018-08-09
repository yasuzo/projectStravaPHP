<?php

namespace Http\Responses;

class StatusCodeResponse{
    private $code;

    public function __construct(int $code){
        $this->code = $code;
    }

    public function send(): void{
        http_response_code($this->code);
        die();
    }
}