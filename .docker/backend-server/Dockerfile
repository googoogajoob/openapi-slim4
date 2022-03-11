FROM php:8.1-apache AS baseimage
WORKDIR /var/www

RUN apt-get update; apt-get -y install vim less iputils-ping libzip-dev zip unzip; \
    apt-get -y -f install mariadb-client git curl gettext-base
RUN docker-php-ext-configure zip --with-zip \
    && docker-php-ext-install zip pdo pdo_mysql
RUN cd; \
    curl -k -sS https://getcomposer.org/installer -o composer-setup.php; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN sed -i 's/\/var\/www\/html$/\/var\/www\/public/' /etc/apache2/sites-available/000-default.conf; \
    sed -i 's/\/var\/www\/html$/\/var\/www\/public/' /etc/apache2/sites-available/default-ssl.conf;
EXPOSE 80
RUN a2enmod rewrite && a2enmod actions && a2enmod proxy_fcgi && a2enmod headers

FROM baseimage AS develop
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;