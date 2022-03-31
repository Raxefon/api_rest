# api_rest
Antes parar todos los servicios (XAMPP, etc).
Arrancando la aplicación de Docker en windows.
Docker-compose up -d

Editamos nuestro archivo de Host para mapear ese dominio contra localhost.
C:\Windows\System32\drivers\etc\hosts
127.0.0.1	   dev.api_rest.com

Comprobamos que todo funcione:
docker ps

Entramos a nuestro contenedor:
docker exec -it api_sesame_php_1 bash

Crear las migraciones:
bin/console doctrine:migrations:migrate

Una vez ya hemos realizado la migracion en postman se prueba:
http://127.0.0.1/api/all_users
