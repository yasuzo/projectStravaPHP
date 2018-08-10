<?php

declare(strict_types=1);

namespace Models;

class User{
    private $id;
    private $firstName;
    private $lastName;
    private $username;
    private $tracking_id;
    private $tracking_token;
    private $picture_url;
    private $organization_id;

    public function __construct(string $firstName, string $lastName, $tracking_id, $tracking_token, string $picture_url,  ?string $username = null, $organization_id = null, $id = null){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->tracking_id = $tracking_id;
        $this->tracking_token = $tracking_token;
        $this->picture_url = $picture_url;
        $this->organization_id = $organization_id;
    }

    /**
     * Returns user's id of application the user has signed with
     *
     * @return $tracking_id 
     */
    public function trackingId(){
        return $this->tracking_id;
    }

    /**
     * Returns user's token provided by the application the user has signed with
     *
     * @return $tracking_token
     */
    public function trackingToken(){
        return $this->tracking_token;
    }

    public function changeTrackingToken($newToken): void{
        $this->tracking_token = $newToken;
    }

    public function username(): ?string{
        return $this->username;
    }

    public function changeUsername(string $username): void{
        $this->username = $username;
    }

    public function organizationId(){
        return $this->organization_id;
    }

    public function changeOrganizationId($organization_id): void{
        $this->organization_id = $organization_id;
    }

    public function firstName(): ?string{
        return $this->firstName;
    }

    public function changeFirstName(string $name): void{
        $this->firstName = $name;
    }

    public function lastName(): ?string{
        return $this->lastName;
    }

    public function changeLastName(string $name): void{
        $this->lastName = $name;
    }

    public function pictureUrl(): ?string{
        return $this->picture_url;
    }

    public function id(){
        return $this->id;
    }
}