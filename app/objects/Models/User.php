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
    private $organization_id;

    public function __construct(string $firstName, string $lastName, string $username, string $tracking_id, string $tracking_token, $organization_id = null, $id = null){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->tracking_id = $tracking_id;
        $this->tracking_token = $tracking_token;
        $this->organization_id = $organization_id;
    }

    /**
     * Returns user's id of application the user has signed with
     *
     * @return string 
     */
    public function trackingId(): string{
        return $this->tracking_id;
    }

    /**
     * Returns user's token provided by the application the user has signed with
     *
     * @return string
     */
    public function trackingToken(): string{
        return $this->tracking_token;
    }

    public function username(): string{
        return $this->username;
    }

    public function changeUsername(string $username): void{
        $this->username = $username;
    }

    public function organizationId(): string{
        return $this->organization_id;
    }

    public function changeOrganizationId($organization_id): void{
        $this->organization_id = $organization_id;
    }

    public function firstName(): string{
        return $this->firstName;
    }

    public function changeFirstName(string $name): void{
        $this->firstName = $name;
    }

    public function lastName(): string{
        return $this->lastName;
    }

    public function changeLastName(string $name): void{
        $this->lastName = $name;
    }

    public function id(){
        return $this->id;
    }
}