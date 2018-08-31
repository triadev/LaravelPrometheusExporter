FROM php:7.1-fpm-alpine

RUN apk add --no-cache --update \
        bash \
        openssh && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        autoconf \
        build-base \
		pcre-dev \
		zlib-dev \
		icu-dev \
        file && \
    docker-php-ext-install pdo_mysql && \
    pecl channel-update pecl.php.net && \
    pecl install apcu apcu_bc-beta && \
    pecl install redis-3.1.1 && docker-php-ext-enable redis && \
    docker-php-ext-enable apcu && \
    docker-php-ext-enable apc && \
    docker-php-ext-install pcntl && \
    docker-php-ext-install posix && \
    rm -f /usr/local/etc/php/conf.d/docker-php-ext-apc.ini && \
    rm -f /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini && \
    curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer && \
    apk del .build-deps && \
    rm -rf /tmp/* /var/tmp/*

# config
COPY ./docker/fpm/apc.ini /usr/local/etc/php/conf.d/apc.ini

# copy entrypoint
COPY ./docker/fpm/entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh

# add log file
RUN mkdir -p /var/www/logs && chown www-data:www-data /var/www/logs

WORKDIR /var/www/html

# add project
COPY . /var/www/html/

ARG user="www-data"

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-R"]
