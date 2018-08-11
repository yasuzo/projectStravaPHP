<?php

declare(strict_types=1);
mb_internal_encoding('UTF-8');

// returns a safe string
function safe(string $str): string {
    return htmlentities($str);
}

// provjerava je li bilo koji od argumenata prazan
function is_empty(...$arr): bool {
    foreach($arr as $val){
        if (empty($val))
            return true;
    }
    return false;
}

// ispisi poruku
function send_message($message): void {
    echo safe((string)$message) . "<br>";
}

// provjera ima li string vise jednog znaka
function longer_than_one(string $str): bool{
    return mb_strlen($str) > 1;
}

//provjerava je li varijabla array
function passed_value_is_array(...$arr): bool {
    foreach($arr as $val){
        if(is_array($val)){
            return true;
        }
    }
    return false;
}

function set_empty_string(&...$arr): void{
    foreach($arr as &$val){
        $val = '';
    }
}

/**
 * Fills $errors variable if any of other parameters are array or empty string
 *
 * @param array|null $errors
 * @param ...$arr
 * @return void
 */
function process_passed_parameters(?array &$errors, ...$arr): void{
    if(passed_value_is_array(...$arr)){
        $errors[] = 'Tip podatka je neisparavan!';
    }
    if(is_empty(...$arr)){
        $errors[] = 'Neka od polja su prazna!';
    }
}

function is_string_number(string $string): bool{
    return (bool)preg_match('/^-?\d+(?:\.\d+)?$/', $string);
}


