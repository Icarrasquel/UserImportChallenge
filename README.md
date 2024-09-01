# Importacion de Usuarios  "UserImportApplication"

Este proyecto tiene como finalidad la importación y/o actualización de usuarios en la base de datos, mediante un
command "commandImportUsers", el cual recibe datos mediante un ENDPOINT.

# Clonar proyecto 

Clone el proyecto y ajuste todos los detalles requeridos para que el proyecto quede completamente operativo.

    1) Agregando el archivo .env, con sus respectivas credenciales de BBDD.
    2) Generando Key.
    3) Instalando composer si no lo tiene instalado.


# Configuración del Crontab para ejecución del command

    1) Debe abrir el su crontad, con el mismo usuario que instalo dicho proyecto.

        Ejecute: crontab -e

    2) Debe agregar la siguiente linea:
        
        */5 * * * * php /var/www/html/UserImportApplication/artisan commandImportUsers >> /dev/null 2>&1

    3) Debe guardar y salir.

    4) Ya deberia quedar operativo el Command.

    5) Para verificar si esta corriendo su crontab, puede verificar con el siguiente comando:

        grep CRON /var/log/syslog

    6) Si quiere probarlo manualmente, ejecute en la ruta del proyecto el siguiente comando:

        php artisan commandImportUsers




