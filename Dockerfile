FROM alpine:edge

WORKDIR /usr/src/app

RUN apk --update add \
      php7-common \
      php7-ctype \
      php7-iconv \
      php7-json \
      php7-mbstring \
      php7-openssl \
      php7-pcntl \
      php7-phar \
      php7-sockets \
      curl \
      git && \
    rm /var/cache/apk/* && \
    ln -s /usr/bin/php7 /usr/bin/php

RUN adduser -u 9000 -D app

COPY . /usr/src/app
RUN chown -R app:app /usr/src/app

USER app

RUN curl -sS https://getcomposer.org/installer | php && \
    ./composer.phar install && \
    rm /usr/src/app/composer.phar

RUN /usr/src/app/vendor/bin/phpcs --config-set \
    installed_paths \
    "/usr/src/app/vendor/drupal/coder/coder_sniffer,/usr/src/app/vendor/wp-coding-standards/wpcs,/usr/src/app/vendor/yiisoft/yii2-coding-standards,/usr/src/app/vendor/magento/marketplace-eqp"

VOLUME /code

CMD ["/usr/src/app/bin/codeclimate-phpcodesniffer"]
