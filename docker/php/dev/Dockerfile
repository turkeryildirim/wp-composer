FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    curl \
    procps \
    zip \
    unzip \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libsqlite3-dev \
    librabbitmq-dev \
    netcat-traditional \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    libmagickwand-dev \
    imagemagick \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        pdo_mysql \
        pdo_sqlite \
        mysqli \
        mbstring \
        pcntl \
        bcmath \
        zip \
        intl \
        sockets \
        xml \
        gd \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && pecl install redis amqp xdebug imagick \
    && docker-php-ext-enable redis amqp xdebug imagick

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

COPY ./php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./init.sh /usr/local/bin/init.sh

RUN mkdir -p /var/www/logs /var/www/app/uploads \
 && groupadd -g 1000 wordpress \
 && useradd -u 1000 -ms /bin/bash -g wordpress wordpress \
 && usermod -aG www-data wordpress \
 && chown -R wordpress:www-data /var/www/app/uploads /var/www/logs \
 && chmod -R 775 /var/www/app/uploads /var/www/logs \
 && chmod +x /usr/local/bin/init.sh

COPY --chown=wordpress:wordpress . /var/www

USER wordpress

EXPOSE 9000 9003

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD [ "sh", "-c", "kill -0 $(cat /var/run/php/php-fpm.pid) 2>/dev/null" ]

CMD ["/usr/local/bin/init.sh"]
