# Welcome to ToDoList  App!

CRUD example using PHP


## Requirements

 1. -  PHP 8.1+
 2. -  MySql 8.0.34+
 3. - Composer 2.2.6+
 4. - Git version 2.34.1+

## Dependencies

 - monolog/monolog => https://github.com/Seldaek/monolog
 - izniburak/router => https://github.com/izniburak/php-router
 - vlucas/phpdotenv => https://github.com/vlucas/phpdotenv
 - josantonius/session => https://github.com/josantonius/php-session

## Installation: Ubuntu
1.- Cone the project: https://github.com/NeftaliAcosta/to-do-list in the folder **/var/www/to-do-list /**

2.- Add the following file **todolist.local.conf** in the folder /etc/apache2/sites-available with the following code:

    VirtualHost *:80>  
       # The ServerName directive sets the request scheme, hostname and port that  
       # the server uses to identify itself. This is used when creating  
       # redirection URLs. In the context of virtual hosts, the ServerName  
       # specifies what hostname must appear in the request's Host: header to  
       # match this virtual host. For the default virtual host (this file) this  
       # value is not decisive as it is used as a last resort host regardless.  
       # However, you must set it for any further virtual host explicitly.  
       #ServerName www.example.com  

       ServerAdmin webmaster@localhost  
       DocumentRoot /var/www/to-do-list 
       ServerAlias todolist.local
     
       # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,  
       # error, crit, alert, emerg.  
       # It is also possible to configure the loglevel for particular  
       # modules, e.g.  
       #LogLevel info ssl:warn  
         
       ErrorLog ${APACHE_LOG_DIR}/error.log  
       CustomLog ${APACHE_LOG_DIR}/access.log combined  
         
       # For most configuration files from conf-available/, which are  
       # enabled or disabled at a global level, it is possible to  
       # include a line for only one particular virtual host. For example the  
       # following line enables the CGI configuration for this host only  
       # after it has been globally disabled with "a2disconf".  
       #Include conf-available/serve-cgi-bin.conf  
     
   	    <Directory /var/www/to-do-list>  
   		    Options Indexes FollowSymLinks  
   		    AllowOverride All  
   		    Require all granted  
   	    </Directory>  
       </VirtualHost>

3.- Run command sudo a2ensite todolist.local.conf

4.- Add the next line  `127.0.1.1 todolist.local`  in hosts file /etc/hosts

5.- Restart apache  `sudo systemctl restart apache2`

6.- In the project files, rename file sample.dev.env to .dev.env

7.- In your database manager, create the database "todoapp" (or how do you prefer to regulate it) **ensure update the .env.dev file**.

8.- run  `composer install` to install dependencies

9.- run  `composer bin-init` To execute database migrations
![enter image description here](https://i.ibb.co/6D3R4kq/migration.png)

**You are done!**
![enter image description here](https://i.ibb.co/M5SmhSq/todoapp.png)


**Additional**: You can also make a Dump of an example database.
https://github.com/NeftaliAcosta/to-do-list/blob/main/todoapp_dump.sql

Access 1:
email: demo@demo.com
Password: Password1

Access 2:
email: demo2@demo.com
Password: Password2
