# P6-Snowtricks
Develop SnowTricks Community Site (SYMFONY) from A to Z

## Config
- PHP 7.4.9
- SYMFONY 5.2
- DOCTRINE 2.8
- COMPOSER 2.0.11
- PHPUNIT 8.5.17
- WAMP 3.2.3
  - APACHE 2.4.46
  - MYSQL 5.7.31

## Install
* Step 1: Move to the installation folder, make a `git clone` followed by `https://github.com/Damien30340/P6-Snowtricks.git`
* Step 2: Configure your environment variables `.env.local` 
* Step 3: Make a `composer install` in command line
* Step 4: Make a `symfony console` or `php bin/console` followed by `doctrine:database:create`
* Step 5: Make a `symfony console` or `php bin/console` followed by `doctrine:migrations:migrate`
* Step 6: Make a `symfony console` or `php bin/console` followed by `doctrine:fixtures:load` 
