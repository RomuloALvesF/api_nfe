FROM php:8.1-apache

# Instalar depend�ncias necess�rias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-install zip soap

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilitar m�dulos necess�rios do Apache
RUN a2enmod rewrite

# Configurar o diret�rio de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do projeto
COPY . .

# Configurar permiss�es para o Apache
RUN chown -R www-data:www-data /var/www/html

# Expor a porta padr�o do Apache
EXPOSE 80
