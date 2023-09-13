
## Install Project

1. Clone project to your system.
2. run "composer install"
3. copy .env.example file and rename it to .env
4. run "php artisan key:generate"
5. install mysql and create your database and run "php artisan migrate"
6. run "php artisan db:seed"
7. for quick test you can run "php artisan serve"
8. for unit testing run "php artisan test"

Requirement: Composer, MySQL, PHP 8.1

## About Project

* Implemented simple Auth without using packages.
* Used custom token for verifying auth.
* Used encrypted passwords.
* Write some unit tests for functions.
* Implemented role based auth for different access control.

## Attentions

* No services used to verify email or phone number. so register and forgot password method implemented without verifying user.
* As a result I considered Admin can register All ROLES and other users only can register as an ACCOUNT.
* In forgot password because there is no verifying method I used email for callback method token.


## FlowChart
![Alt text](./doc/tasks.png)

I Implemented this project in a day.

Thank you for your attention!
