# IndicadoresDCC
Ingenieria de Software 2

1.- instalar xampp (esta pa win, macy linux)
https://www.apachefriends.org/index.html

2.- abrir php.ini (C:\xampp\php\php.ini)
* linea 535 dejar: error_reporting=E_ALL (asi reporta todas los errores sirve para debugear)
* linea 552: display_errors=On
* linea 564: display_startup_errors=On
* linea 574: log_errors=On
* linea 608: track_errors=On
* linea 626: html_errors=On
* linea 1046: date.timezone=America/Santiago (para que la hora si invocan alguna funcion php estilo date() la hora de bn)
* linea 990-1021: son los drivers a cargar revisen que extension=php_mysql.dll este descomentada (driver mysql)

3.- abrir httpd.conf (C:\xampp\apache\conf\httpd.conf)
* linea 264: AllowOverride All (esto es para lograr re-escribir la url a un formato user friendly, ojo que si no modifican esto el proyecto actual no les va a funcionar)
* linea 304: LogLevel debug (para debugear)

partir xamp

meter proyecto a la carpeta C:\xampp\htdocs (ejemplo C:\xampp\htdocs\IndicadoresDCC)

y luego en browser poner http://localhost/IndicadoresDCC y les deberia andar
si ponen solo http://localhost les va a partir la vista de xampp por defecto

Suerte



************** un par de tips ***************

cree una libraria, controlador y modelo de metricas a modo de ejemplo a demas deje varias otras funciones de ejemplos.


para acceder a una funcion especifica de un controlador

http://localhost/IndicadoresDCC/Nomnbre del controlador/nombre de la funcion

ejemplo:

http://localhost/IndicadoresDCC/Metricas/listAllMetrics (esto funciona copienlo y peguenlo en su navegador)

como aun no esta arriba la bd en el servidor orion y ademas solo se puede acceder a ella dentro de la red local
yo instale una bd en mi oficina si van a la carpeta config/database.php pueden configurar para poder entrar desde afuera
es simplemente cambiar la url (sale explicado ahi)
