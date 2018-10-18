# BlogOC

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/049008c476e0480ea032ec4fb7ab8a27)](https://app.codacy.com/app/alexandre-mace/oc_p5?utm_source=github.com&utm_medium=referral&utm_content=alexandre-mace/oc_p5&utm_campaign=Badge_Grade_Dashboard)

School blog project : Create a PHP Blog with admin system using composer and twig.

## Website deployment process

 * Create a database and import the blog_oc_db.sql file located at the project's root.

 * Configure your virtualhost and make it aim to the web directory, and use the environment variables to configure your database and the mailer.

 Here is an example of mine (WAMP + Windows 10) :

<VirtualHost *:80>
    ServerName blogOC.local
    ServerAlias localhost
    DocumentRoot C:\wamp64\www\blogOC
    SetEnv ENV "dev"
    SetEnv DB_HOST "your host"
    SetEnv DB_NAME "blog_oc_db"
    SetEnv DB_USER "root"
    SetEnv DB_PASSWORD ""
    SetEnv MAIL_USER "your email"
    SetEnv MAIL_PASSWORD "your password"
    <Directory "C:\wamp64\www\blogOC">
        Options +Indexes +Includes +FollowSymLinks +MultiViews
        Require local
        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ web/index.php [QSA,L]
        </IfModule>
    </Directory>
</VirtualHost>

Moreover, you also have to adapt the 30rd line of the /src/Controller/ContactController to have the right 'smtp' protocol. 
Here is an example :
$transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))

## Theme used
I used the clean blog theme for this project. You can find it here https://startbootstrap.com/template-overviews/clean-blog/ which is currently working with bootstrap 4.

## Administration section
The database comes with an administrator already registered in it.
id = 'a'
password = 'aaaaaa'

The connection section is accessible at the bottom of any page.
    
 
 
