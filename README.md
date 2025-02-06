Package à installer : 
apt-get install php-pdo php-pdo_mysql php-curl

Requête à faire dans la BDD postgreSQL : 
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

conf apache : 
```conf
<VirtualHost *:80>
	ServerName www.example.com
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/site

	<Directory /var/www/site>
		Options Indexes FollowSymLinks
		AllowOverride All
		Require all granted
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```