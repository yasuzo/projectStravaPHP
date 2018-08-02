<?php

namespace Models;

class Organization{
    private $id;
    private $name;
    private $longitude;
    private $latitude;

    public function __constuct(string $name, Point $point, $id = null){
        $this->name = $name;
        $this->id = $id;
        $this->longitude = $point->longitude();
        $this->latitude = $point->latitude();
    }

    public function id(){
        return $this->id;
    }

    public function name(): string{
        return $this->name;
    }

    public function changeName(string $name){
        $this->name = $name;
    }

    public function changeCoordinates(Point $point){
        $this->longitude = $point->longitude();
        $this->latitude = $point->latitude();
    }

    public function longitude(): float{
        return $this->longitude;
    }

    public function latitude(): float{
        return $this->latitude;
    }
}