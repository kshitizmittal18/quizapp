# Demo Quiz App: Laravel 5.6

It is a demo quiz application.


## How to setup:

#### Steps:
- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__
- Run __php artisan key:generate__
- Run __php artisan migrate --seed__ (it has some seeded data for your testing).
- Now you can login as admin: launch the main URL and login with default credentials __admin@admin.com__ - __password__
- Fill in the database with topics, questions and options 
- Click on questions from admin account and add questions by calling API.
- Register and take quizzes!

