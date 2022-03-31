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

# Entidad USER

### Crear User:
POST http://127.0.0.1/api/create_user

body:
>{
  "name": "test",
  "email": "test@test.com"
}

### Actualizar User:
PUT http://127.0.0.1/api/update_user/{id}

>body:
{
	"name": "test2",
	"email": "test2@test.com"
}

### Borrar User:
http://127.0.0.1/api/delete_user/{id}

### Mostrar User por id:
GET http://127.0.0.1/api/user/{id}

### Mostrar todos los usuarios:
GET http://127.0.0.1/api/all_users

# Entidad WorkEntry

### Crear WorkEntry:
POST http://127.0.0.1/api/create_workEntry

>body:
{
	"user": "1"
}

### Actualizar WorkEntry por id:
PUT http://127.0.0.1/api/update_workEntry/{id}

>body:
{
	"user": "1",
	"startDate": "2022-03-29T13:29:36+02:00"
}

### Borrar WorkEntry por id:
DELETE http://127.0.0.1/api/delete_workEntry/{id}

### Mostrar WorkEntry por id:
GET http://127.0.0.1/api/workEntry/{id}

### Mostrar WorkEntry por Userid:
GET http://127.0.0.1/api/workEntryByUserId/{id}


 
