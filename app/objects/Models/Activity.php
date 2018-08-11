<?php

namespace Models;

use Models\Point;

class Activity{
    private $id;
    private $type;
    private $distance;
    private $duration;
    private $polyline;
    private $latitude;
    private $longitude;
    private $started_at;
    private $ended_at;
    private $tracking_id;
    private $user_id;
    private $organization_id;

    public function __construct(string $type, int $distance, int $duration, string $polyline, Point $point, int $started_at, int $ended_at, int $tracking_id, int $user_id, ?int $organization_id, ?int $id = null){
        $this->id = $id;
        $this->type = $type;
        $this->distance = $distance;
        $this->duration = $duration;
        $this->polyline = $polyline;
        $this->latitude = $point->latitude();
        $this->longitude = $point->longitude();
        $this->started_at = $started_at;
        $this->ended_at = $ended_at;
        $this->tracking_id = $tracking_id;
        $this->user_id = $user_id;
        $this->organization_id = $organization_id;
    }

    public function id(): ?int{
        return $this->id;
    }

    public function organizationId(): ?int{
        return $this->organization_id;
    }

    public function type(): string{
        return $this->type;
    }

    public function distance(): int{
        return $this->distance;
    }

    public function duration(): int{
        return $this->duration;
    }

    public function polyline(): string{
        return $this->polyline;
    }

    public function latitude(): float{
        return $this->latitude;
    }

    public function longitude(): float{
        return $this->longitude;
    }

    public function startedAt(): int{
        return $this->started_at;
    }

    public function endedAt(): int{
        return $this->ended_at;
    }

    public function trackingId(): int{
        return $this->tracking_id;
    }

    public function userId(): int{
        return $this->user_id;
    }
    
}