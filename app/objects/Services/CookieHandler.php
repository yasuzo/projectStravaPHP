<?php

namespace Services;

class CookieHandler{
    private $cookies;

    public function __construct(array $cookies){
        $this->cookies = $cookies;
    }

    /**
     * Sets a cookie
     *
     * @param string $name
     * @param integer $secondsFromNow
     * @param string ...$strings
     * @return void
     */
    public function setCookie(string $name, int $secondsFromNow, string ...$strings){
        $value = implode('|', $strings);
        \setcookie($name, $value, time() + $secondsFromNow);
    }

    /**
     * Reads a cookie
     *
     * @param string $name
     * @throws \OutOfBoundsException
     * @return string
     */
    public function readCookie(string $name): string{
        if(isset($this->cookies[$name]) === false){
            throw new \OutOfBoundsException('Cookie does not exist!');
        }
        return $this->cookies[$name];
    }

    /**
     * Deletes a cookie by name
     *
     * @param string $name
     * @return void
     */
    public function deleteCookie(string $name){
        \setcookie($name, '', 1);
    }

    /**
     * Returns array stored in a cookie as a string
     *
     * @param string $name
     * @throws \OutOfBoundsException
     * @return array
     */
    public function readCookieArray(string $name): array{
        if(isset($this->cookies[$name]) === false){
            throw new \OutOfBoundsException('Cookie does not exist!');
        }
        return \explode('|', $this->cookies[$name]);
    }
}