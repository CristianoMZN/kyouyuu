FROM php:8.4.4-fpm

# Atualiza os pacotes e instala dependências necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev libmemcached-dev unzip curl libonig-dev libpq-dev libssl-dev \
    librabbitmq-dev libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_mysql

# Instala extensões PECL
RUN pecl install xdebug memcached amqp \
    && docker-php-ext-enable xdebug memcached amqp

# Cria diretório de sessões e define permissões
RUN mkdir -p /var/lib/php/sessions && chown -R www-data:www-data /var/lib/php/sessions

# Define permissões adequadas para evitar problemas de acesso
RUN chown -R www-data:www-data /var/www/html