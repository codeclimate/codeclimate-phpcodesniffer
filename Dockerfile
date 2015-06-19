FROM alpine:edge

WORKDIR /usr/src/app
COPY composer.json /usr/src/app/
COPY composer.lock /usr/src/app/

RUN apk --update add php-common php-iconv php-json php-phar php-openssl curl && \
    curl -sS https://getcomposer.org/installer | php && \
    /usr/src/app/composer.phar install && \
    apk del build-base && rm -fr /usr/share/ri

COPY . /usr/src/app

CMD ["/usr/src/app/bin/codeclimate-phpcodesniffer"]
