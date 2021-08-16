FROM php:7.4-apache AS baseimage
WORKDIR /var/www
RUN apt-get update
RUN apt-get -y install vim less iputils-ping libzip-dev zip unzip
RUN apt-get -y -f install mariadb-client git curl gettext-base
RUN docker-php-ext-configure zip --with-zip
RUN docker-php-ext-install zip pdo pdo_mysql
RUN cd; \
    curl -k -sS https://getcomposer.org/installer -o composer-setup.php; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN sed -i 's/\/var\/www\/html$/\/var\/www\/public/' /etc/apache2/sites-available/000-default.conf; \
    sed -i 's/\/var\/www\/html$/\/var\/www\/public/' /etc/apache2/sites-available/default-ssl.conf;
EXPOSE 80
RUN a2enmod rewrite && a2enmod actions && a2enmod proxy_fcgi && a2enmod headers

FROM baseimage AS develop
RUN pecl install xdebug-2.9.1
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini; \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/php.ini; \
    echo "xdebug.remote_host=192.168.10.213" >> /usr/local/etc/php/php.ini