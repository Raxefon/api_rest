# api_rest
Antes parar todos los servicios (XAMPP, etc).
Arrancando la aplicación de Docker en windows.
Docker-compose up -d

Editamos nuestro archivo de Host para mapear ese dominio contra localhost.
C:\Windows\System32\drivers\etc\hosts
127.0.0.1	   dev.api_rest.com

Comprobamos que todo funcione:
Docker ps

Entramos a nuestro contenedor:
docker exec -it api_sesame_php_1 bash

Como no hemos comenzado con docker, borraremos la carpeta vendor del proyecto.

Desde dentro del contenedor ejecutaremos:
Composer install

El composer.json no contiene el bundle FOS REST( rest-bundle) así que ejecutamos:
Composer require friendsofsymfony/rest-bundle

Volver a crear las migraciones:
bin/console doctrine:migrations:migrate

Una vez ya hemos realizado la migracion en postman se prueba:
http://127.0.0.1/api/all_users![image](https://user-images.githubusercontent.com/10602033/161007118-fc6488d4-87d0-42d3-aefd-cefe6ea2411d.png)
