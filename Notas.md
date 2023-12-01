### Aqui se pondran algunas notas importentes a lo largo del curso

FE: FrontEnd
BD: Base de datos
BE: BackEnd

## Que archivos editar en el FrontEnd "FE"

Simpre hay que editar en la carpeta **src/js** o **scss/base** 
esto se mira reflejado en los archivos compilados que estan en public


## Restricciones de Integridad Referencial "BD"

Esto tiene que ver con las relaciones de la 
base de datos es decir hay que estudiar bien que
se puede eliminar y que no al querer eliminar un 
registro que actualemnte se usa en otra tabla relacionada
Estudiar antes de relacionar tablas
Existen: 
- set null
- cascade
- no action
- restrict


## Instalacion de una nueva libreria con composer "BE"

Al momento de instalar una nueva libreria con composer
es necesario ejecutar el siguiente comando.

-> Composer update

este nos servira para recargar el Autoload y que carge la nueva libreria



SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ' ,usuarios.apellido) AS cliente, usuarios.email, usuarios.telefono, servicios.nombre AS servicio, servicios.precio
FROM citas 
LEFT OUTER JOIN usuarios 
ON citas.usuarioId=usuarios.id
LEFT OUTER JOIN citasservicios
ON citasservicios.citaId=citas.id
LEFT OUTER JOIN servicios
on servicios.id=citasservicios.servicioId;