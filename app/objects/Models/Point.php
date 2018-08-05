<?php

namespace Models;

class Point{
    private $longitude;
    private $latitude;

    /**
     * Point constructor
     *
     * @param string $longitude
     * @param string $latitude
     * 
     * @throws \InvalidArgumentException
     * @throws \DomainException
     */
    public function __construct(string $longitude, string $latitude){
        $this->sanitizeCoordinate($longitude);
        $this->sanitizeCoordinate($latitude);
        
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function longitude(): float{
        return $this->longitude;
    }

    /**
     * Changes longitude
     *
     * @param string $long
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return void
     */
    public function changeLongitude(string $long): void{
        $this->sanitizeCoordinate($long);

        $this->longitude = $long;
    }

    public function latitude(): float{
        return $this->latitude;
    }

    /**
     * Changes latitude
     * 
     * @param string $lat
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return void
     */
    public function changeLatitude(string $lat): void{
        $this->sanitizeCoordinate($lat);
        $this->latitude = $lat;
    }

    // TODO: Implement
    public static function distance(Point $a, Point $b): float{

    }

    // TODO: Implement
    public static function lastPointOfPolyline(string $polyline): Point{

    }


    /**
     * Throws exceptions if a coordinate is invalid
     *
     * @param string $coordinate
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return void
     */
    private function sanitizeCoordinate(string &$coordinate): void{
        if(\is_string_number($coordinate) === false){
            throw new \InvalidArgumentException('Coordinate given is not a number!');
        }
        
        $coordinate = round($coordinate, 5);

        if($coordinate > 180 || $coordinate < -180){
            throw new \DomainException('Coordinate is not within the domain!');
        }
    }
}