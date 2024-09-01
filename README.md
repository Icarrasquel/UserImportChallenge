# Importacion de Usuarios  "UserImportApplication"

Este proyecto tiene como finalidad la importacion y/o actualizacion de usuarios en la base de datos, mediantes un
command "commandImportUsers", el cual recibe datos mediante un ENDPOINT.

# Clonar proyecto 

    Clone el proyecto y ajuste todos los detalles requeridos para que el proyecto quede completamente
    operativo.

        Agregado el archivo .env, con sus respectivas credenciales de BBDD.
        Generando Key.
        Intalando composer sino lo tiene instalado.


# IMPORTANTE: Configuracion del Crontab para ejecución del command

    1) Debe abrir el su crontad, con el mismo usuario que instalo dicho proyecto.

        Ejecute: crontab -e

    2) Debe agregar la siguiente linea:
        
        */5 * * * * php /var/www/html/UserImportApplication/artisan commandImportUsers >> /dev/null 2>&1

    3) Debe guardar y salir.

# Ya deberia quedar operativo el Command.

# Para verificar si esta corriendo su crontab, puede verificar con el siguiente comando:

    grep CRON /var/log/syslog

# Si quiere probarlo manualmente, ejecute en la ruta del proyecto el siguiente comando:

    php artisan commandImportUsers

# FIN



