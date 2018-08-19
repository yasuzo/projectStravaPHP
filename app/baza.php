<?php

try {
    $dsn = 'mysql:host=localhost;charset=utf8;dbname=strava';
    $db = new PDO(
        $dsn,
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_DEFAULT_FETCH_MODE =>
            PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE =>
            PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) { throw $e; }
