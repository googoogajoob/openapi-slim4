ARG PHP_VERSION
FROM php:$PHP_VERSION-apache AS baseimage
WORKDIR /var/www

ARG COMPOSER_ALLOW_SUPERUSER=1
ENV LOGLEVEL=DEBUG
ENV LOGPATH=php://stderr
ENV BASE_DIRECTORY=/var/www

RUN apt-get update; apt-get -y install vim less iputils-ping libzip-dev zip unzip; \
    apt-get -y -f install curl gettext-base
RUN docker-php-ext-configure zip --with-zip \
    && docker-php-ext-install zip
RUN cd; \
    curl -k -sS https://getcomposer.org/installer -o composer-setup.php; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN sed -i 's/\/var\/www\/html$/\/var\/www\/public/' /etc/apache2/sites-available/000-default.conf; \
    sed -i 's/\/var\/www\/html$/\/var\/www\/public/' /etc/apache2/sites-available/default-ssl.conf;
EXPOSE 80
RUN a2enmod rewrite && a2enmod actions && a2enmod proxy_fcgi && a2enmod headers

FROM baseimage AS develop
ARG XDEBUG_VERSION
RUN pecl install xdebug-$XDEBUG_VERSION
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;