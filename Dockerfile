# Usamos una versión estable de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias para Composer y librerías
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip \
    && a2enmod rewrite \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# 3. Configurar Apache automáticamente (ESTO REEMPLAZA TU ARCHIVO EXTRA)
# Cambiamos DocumentRoot a /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-enabled/000-default.conf
# Cambiamos AllowOverride None a All para que el .htaccess funcione
RUN sed -i '/<Directory \/var\/www\/html\/public>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf || \
    echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# 4. Gestión de usuarios (Similar a tu Nginx)
# Esto evita que los archivos creados por el contenedor tengan permisos de root
ARG UID=1000
ARG GID=1000
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

# Instalar Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Instalamos todo en una sola capa para optimizar el caché


WORKDIR /var/www/html
COPY . .