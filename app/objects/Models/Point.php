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
        $this->sanitizeCoordinates($latitude, $longitude);
        
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

    /**
     * Calculates distance between two points on earth in meters
     *
     * @param Point $a
     * @param Point $b
     * @return float distance in meters
     */
    public static function distance(Point $a, Point $b): float{
        $r = 6371000;
        $fi1 = deg2rad($a->latitude());
        $fi2 = deg2rad($b->latitude());
        $dFi = deg2rad($b->latitude() - $a->latitude());
        $dLambda = deg2rad($b->longitude() - $a->longitude());
        
        $a = sin($dFi / 2) * sin($dFi / 2) + cos($fi1) * cos($fi2) * sin($dLambda / 2) * sin($dLambda / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $d = $r * $c;
        return $d;
    }

    /**
     * Calculates last point of polyline
     *
     * @param string $polyline
     * @return Point
     */
    public static function lastPointOfPolyline(string $polyline): Point{
        $points = array();
        $index = $i = 0;
        $previous = array(0,0);
        while ($i < strlen($polyline)) {
            $shift = $result = 0x00;
            do {
                $bit = ord(substr($polyline, $i++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while ($bit >= 0x20);
            $diff = ($result & 1) ? ~($result >> 1) : ($result >> 1);
            $number = $previous[$index % 2] + $diff;
            $previous[$index % 2] = $number;
            $index++;
            $points[] = $number * 1 / pow(10, 5);
        }
        $lng = array_pop($points);
        $lat = array_pop($points);

        return new Point($lng, $lat);
    }


    /**
     * Throws exceptions if a coordinate is invalid
     *
     * @param string $coordinate
     * @throws \InvalidArgumentException
     * @throws \DomainException
     * @return void
     */
    private function sanitizeCoordinates(string &$latitude, string &$longitude): void{
        if(\is_string_number($latitude) === false || \is_string_number($longitude) === false){
            throw new \InvalidArgumentException('Coordinate given is not a number!');
        }
        
        $latitude = round($latitude, 5);
        $longitude = round($longitude, 5);

        if($longitude > 180 || $longitude < -180){
            throw new \DomainException('Longitude is not within the domain!');
        }

        if($latitude > 90 || $latitude < -90){
            throw new \DomainException('Latitude is not within the domain!');
        }
    }
}