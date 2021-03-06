<?php

declare(strict_types=1);

require_once 'helper_functions.php';

use Services\UserRepository;

define('MIN_LEN_USER', 1);
define('MAX_LEN_USER', 40);


function validate_name(string $firstName, string $lastName, ?array &$errors){
    $regex = "/[a-zA-Z\p{L}][a-zA-Z\p{L}]/u";
    // if((bool)preg_match($regex, $firstName) === false || (bool)preg_match($regex, $lastName) === false){
    //     $errors[] = "Ime i prezime smije sadrzavati samo slova i mora biti dugacko od 1 do 25 slova!";
    //     return false;
    // }

    if(mb_strlen($firstName) > 25 || (bool)preg_match($regex, $firstName) === false || mb_strlen($lastName) > 25 || (bool)preg_match($regex, $lastName) === false){
        $errors[] = "Ime i prezime ne smiju sadržavati više od 25 znakova i moraju imati barem dva slova!";
        return false;       
    }
    return true;
}

function validate_username(string $username, ?array &$errors): bool{
    $regex = "/^(?=[\w\-]*[a-zA-Z\p{L}])[\w\-]{".MIN_LEN_USER.",".MAX_LEN_USER."}$/u";
    if((bool)preg_match($regex, $username) === false){
        $errors[] = "Korisničko ime smije sadržavati slova, brojke i _,- te mora posjedovati barem jedno slovo!";
        return false;
    }
    return true;
}

function validate_passwords(string $pass1, string $pass2, ?array &$errors): bool{
    $regex = "/^(?=.*\d).{12,}$/u";
    if((bool)preg_match($regex, $pass1) === false){
        $errors[] = "Lozinka mora sadržavati barem 12 znakova i barem jedan od tih znakova mora biti broj!";
        return false;
    }else if($pass1 !== $pass2){
        $errors[] = "Lozinke nisu iste!";
        return false;
    }
    return true;
}

function username_taken(string $username, UserRepository $userRepository, ?array &$errors): bool{
    if($userRepository->findByUsername($username) !== null){
        $errors[] = "Korisničko ime već postoji!";
        return true;
    }
    return false;
}

// function valid_date_time(string $date_time): bool{
//     list($date, $time) = explode(' ', $date_time);
//     $date = DateTime::createFromFormat("Y-m-d", $date);
//     $convertedTime = DateTime::createFromFormat("H:i:s", $time);
//     if(($convertedTime !== false && !array_sum($convertedTime->getLastErrors())) === false){
//         $convertedTime = DateTime::createFromFormat("H:i", $time);
//     }
//     return $date !== false && !array_sum($date->getLastErrors()) && $convertedTime !== false && !array_sum($convertedTime->getLastErrors());
// }