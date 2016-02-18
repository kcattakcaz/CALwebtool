## CAL Web Tool

The CAL Web Tool is a web application implemented using the Larvel 5.2 PHP Framework that enables management of scholarship application forms, submissions, and scoring.

## Official Documentation

Documentation for the framework can be found on the [Coming Soon](https://calwebtool.jaghory.com/help).

## Security Vulnerabilities

If you discover a security vulnerability within this tool, please send an e-mail to the repository owners.

### Installation Instructions

1. Download this repository
2. Complete all empty paramters in the production.env or .env file
3. Run 'composer update'
3. Set the application key by 'php artisan key:generate'
4. Run the database migrations by 'php artisan migrate'
5. Seed the database with the default user and group 'php artisan db:seed'
6. Change the System Administrator's password, by default it is 'password'