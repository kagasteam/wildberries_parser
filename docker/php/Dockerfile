#syntax=docker/dockerfile:1.4
FROM php:8.2-fpm-alpine3.17

WORKDIR /var/www/html

RUN set -eux; \
    apk update; \
    apk add --no-cache shadow git zsh patch; \
    apk del; \
    rm -rf /var/lib/apt/lists/*; \
    sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" -- --unattended;

RUN apk add icu-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-configure intl && docker-php-ext-install intl

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer ; \
    ln -s composer /usr/local/bin/c;

COPY --link php.ini $PHP_INI_DIR/conf.d/
COPY --link fpm.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
RUN set -eux; \
    cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
    mkdir /root/history /var/run/php;



