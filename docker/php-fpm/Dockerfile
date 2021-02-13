FROM alpine:3.13

LABEL maintainer="Vincent Composieux <vincent.composieux@gmail.com>"

RUN apk add --no-cache \
    bash \
    git \
    coreutils \
    php7-fpm \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-json \
    php7-intl \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-zip \
    php7-xmlwriter \
    php7-pecl-apcu \
    make \
    curl

# Allow to enable php7-xdebug
ARG ENABLE_PHP_XDEBUG
RUN if [[ "$ENABLE_PHP_XDEBUG" == "1" ]]; then \
        apk add --no-cache php7-pecl-xdebug; \
    fi

RUN curl -sS https://getcomposer.org/installer | tee composer-setup.php \
    && php7 composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer \
    && ln -s /usr/bin/php7 /usr/local/bin/php

COPY symfony.ini /etc/php7/conf.d/
COPY symfony.ini /etc/php7/cli/conf.d/
COPY xdebug.ini  /etc/php7/conf.d/

COPY symfony.pool.conf /etc/php7/php-fpm.d/

WORKDIR /var/www/symfony

CMD ["php-fpm7", "-F"]

EXPOSE 9001
