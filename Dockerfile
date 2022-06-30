FROM alpine:3.14.3

RUN adduser -u 9000 -D app

WORKDIR /usr/src/app

RUN apk add --no-cache \
      ca-certificates  \
      php7 \
      php7-common \
      php7-ctype \
      php7-iconv \
      php7-json \
      php7-mbstring \
      php7-openssl \
      php7-pcntl \
      php7-phar \
      php7-simplexml \
      php7-sockets \
      php7-tokenizer \
      php7-xml \
      php7-xmlreader \
      php7-xmlwriter \
      php7-zlib

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./

RUN apk add --no-cache git && \
    composer install --no-dev && \
    apk del --purge git && \
    vendor/bin/phpcs --config-set \
      installed_paths \
      "/usr/src/app/vendor/drupal/coder/coder_sniffer,/usr/src/app/vendor/escapestudios/symfony2-coding-standard,/usr/src/app/vendor/wp-coding-standards/wpcs,/usr/src/app/vendor/yiisoft/yii2-coding-standards,/usr/src/app/vendor/magento/marketplace-eqp,/usr/src/app/vendor/magento/magento-coding-standard,/usr/src/app/vendor/pheromone/phpcs-security-audit" && \
    chown -R app:app . && \
    rm -r ~/.composer

COPY . ./

RUN find -not \( -user app -and -group app \) -exec chown -R app:app {} \;

USER app

VOLUME /code

CMD ["/usr/src/app/bin/codeclimate-phpcodesniffer"]
