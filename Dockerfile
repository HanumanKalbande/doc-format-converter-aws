# Download base with Ubuntu and php 8.2
 
FROM php:8.2-cli
COPY . /usr/run/php
 
# LABEL about the custom image
LABEL maintainer="Hanuman Kalbande"
 
#Update Ubuntu Software repository
RUN apt-get update

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install curl xml

#install ghostscript
RUN apt -y install ghostscript
 
#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
 
RUN composer require aws/aws-sdk-php
 
WORKDIR /usr/run/php
#CMD [ "php", "index.php" ]