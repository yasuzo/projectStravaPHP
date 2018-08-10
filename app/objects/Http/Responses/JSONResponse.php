<?php

namespace Http\Responses;

class JSONResponse implements Response {
    private $json;

    public function __construct(array $seq_array){
        $this->json = json_encode($seq_array);
    }

    public function send(): void {
        header('Content-Type: application/json');
        echo $this->json;
    }
}