FROM php:8.2-apache

# Install and enable mysqli
RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

COPY OBR_QMS/ /var/www/html/

EXPOSE 80
