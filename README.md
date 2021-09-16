## Description

Pharmacy API - IPMS<br>
How API works schema - https://miro.com/app/board/o9J_l5pwayI=/

## Used technologies
* Laravel (v8.58.0)
* PHP (v8.0.3)
* MySQL (latest)

## Setup Guide

Before developing, install packages by running:

    composer install

Create a copy of .env file:

    cp .env.example .env

Generate an application encryption key:

    php artisan key:generate


### Database

Edit Database connections in .env file

To create, populate and seed database run:

    php artisan migrate:fresh --seed


### Running

To start the server run:

    php artisan serve


### Testing

To start tests run:

    php artisan test
