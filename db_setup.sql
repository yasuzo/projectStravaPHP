CREATE DATABASE `strava`
CHARACTER SET='utf8mb4' 
COLLATE='utf8mb4_general_ci';

USE `strava`;

CREATE TABLE users(
    id          int AUTO_INCREMENT primary key,
    firstName   varchar(25) not null,
    lastName    varchar(25) not null,
    username    varchar(40) unique,
    picture_url varchar(500),
    tracking_id int unique not null,
    tracking_token varchar(256) not null,
    organization_id int
);

CREATE TABLE activities(
    id          int AUTO_INCREMENT primary key,
    type        varchar(30) not null,
    distance    int not null,
    duration    int not null,
    polyline    varchar(5000) not null,
    latitude    int not null,
    longitude   int not null,
    started_at  timestamp not null,
    ended_at    timestamp not null,
    tracking_id int unique not null,
    user_id     int not null,
    FOREIGN key (user_id)
        REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
);

CREATE TABLE organizations(
    id          int AUTO_INCREMENT primary key,
    name        varchar(50) unique not null,
    latitude    int not null,
    longitude   int not null
);

CREATE TABLE admins(
    id                  int AUTO_INCREMENT primary key,
    username            varchar(40) unique not null,
    password            varchar(256) not null,
    type                enum('admin', 'superadmin') not null,
    organization_id     int,
    FOREIGN Key (organization_id)
        REFERENCES organizations (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
);

CREATE TABLE bans(
    id      int AUTO_INCREMENT primary key,
    user_id int not null,
    organization_id     int not null,
    FOREIGN key (user_id)
        REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    FOREIGN key (organization_id)
        REFERENCES organizations (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
);

ALTER TABLE users
ADD CONSTRAINT connection_user_organization
FOREIGN KEY (organization_id)
REFERENCES organizations (id)
ON DELETE SET null
ON UPDATE RESTRICT;

CREATE TABLE webhook_events(
    id      int AUTO_INCREMENT primary key,
    data    TEXT not null
);

ALTER TABLE `activities` CHANGE `ended_at` `ended_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE activities
ADD organization_id int;

ALTER TABLE activities
ADD CONSTRAINT connection_activity_organization
FOREIGN KEY (organization_id)
REFERENCES organizations (id)
ON DELETE SET null
ON UPDATE RESTRICT;

CREATE TABLE sessions(
    session_id          varchar(32) primary key,
    user_id             int,
    type                varchar(30),
    session_data        text not null,
    last_access_time    timestamp not null DEFAULT CURRENT_TIMESTAMP
);