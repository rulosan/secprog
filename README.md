# Se tiene que bajar el composer.phar

curl -sS https://getcomposer.org/installer | php

# Luego se instalan dependencias con composer

php composer.phar install

# Luego se crea carpeta de uploads

mkdir -p uploads