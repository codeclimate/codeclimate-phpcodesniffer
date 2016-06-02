FROM alpine:3.3

WORKDIR /usr/src/app
COPY composer.json /usr/src/app/
COPY composer.lock /usr/src/app/

RUN apk --update add git php-common php-ctype php-iconv php-json php-phar php-pcntl php-openssl php-sockets curl && \
    curl -sS https://getcomposer.org/installer | php && \
    /usr/src/app/composer.phar install && \
    apk del build-base && rm -fr /usr/share/ri

RUN /usr/src/app/vendor/bin/phpcs --config-set installed_paths /usr/src/app/vendor/drupal/coder/coder_sniffer

RUN adduser -u 9000 -D app
USER app

COPY . /usr/src/app

CMD ["/usr/src/app/bin/codeclimate-phpcodesniffer"]
