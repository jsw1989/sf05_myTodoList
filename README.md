# Todo-list

website with Symfony 5 allowing to create, edit, delete and list tasks to be performed.

This application is built with symfony5.

Requirements
PHP 7.2
Mysql 5.7


Process of Test:
1- clone the Git repository to local server.\n
2- on the project folder run composer install , to install symfony dependencies.\n
3- run npm install to install js dependencies.\n
4- change .env file and configure the acess to database:
DATABASE_URL=mysql://user_name:password@127.0.0.1:3306/sf05_tododb?serverVersion=5.7
change your own usern_name and your password.
5- run bin/deploy.cmd : this command will create database and run fixtures.
6- execute on the application folder : php -S localhost:8001 -t public/
this will run the server .
7- open browser and go to http://localhost:8001/.
