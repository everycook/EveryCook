EveryCook
=========
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant.
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

Installation
------------
precondition:
You need a XAMP (Apache, Mysql, PHP) installation.

1.  configure Apache  
	Activate/install the following modules in your apache config:  
	mod\_rewrite  
	mod\_memcached (apt-get install php5-memcached)  
	
	Set "AllowOverride FileInfo" for Everycook's "db" direcory in your apache/vhost config.  
	This is needed for mod\_rewrite takes effect.

2.	install needed software  
	Install the memcached Server. (apt-get install memcached)

3.	checkout this git repository to the "db" directory in your webserver path.  
	git clone git://github.com:/everycook/EveryCook.git /var/www/db  
	Change filesystem permission so webserver user (www-data) can write to db/assets, db/img, db/cache and db/protected/runtime.

4.	import DB dump  
	Get the EveryCook_DB.sql.gz sql dump from [Downloads Page](http://everycook.org/cms/downloads/) and import it to your mysql.  
	It will create databases with name "ec" and "ec_priv".

5.	get images  
	Get EveryCook_images.tgz from [Downloads Page](http://everycook.org/cms/downloads/) and extract its img folder to /var/www/db/img these images are linked from the database.

6.	Change yii config settings  
	rename, copy or link db/protected/config/entw.php to db/protected/config/main.php  
	Change user and password for database connection "db" and "dbp".  
	Setting E-Mail host, user, passwort for sending registration Mail in 'params' section.  

