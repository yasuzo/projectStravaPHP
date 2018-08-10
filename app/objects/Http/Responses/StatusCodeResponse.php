<?php

namespace Http\Responses;

class StatusCodeResponse implements Response{
    private $code;

    public function __construct(int $code){
        $this->code = $code;
    }

    public function send(): void{
        http_response_code($this->code);
        die();
    }
}