FROM alpine:edge

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
      php7-xmlwriter \
      php7-zlib && \
    EXPECTED_SIGNATURE=$(php -r "echo file_get_contents('https://composer.github.io/installer.sig');") && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');") && \
    [ "$EXPECTED_SIGNATURE" = "$ACTUAL_SIGNATURE" ] || (echo "Invalid Composer installer signature"; exit 1) && \
    php composer-setup.php --quiet && \
    mv composer.phar /usr/local/bin/composer && \
    rm -r composer-setup.php /var/cache/misc/* ~/.composer

COPY composer.json composer.lock ./

RUN apk add --no-cache git && \
    composer install --no-dev && \
    apk del --purge git && \
    vendor/bin/phpcs --config-set \
      installed_paths \
      "/usr/src/app/vendor/drupal/coder/coder_sniffer,/usr/src/app/vendor/escapestudios/symfony2-coding-standard,/usr/src/app/vendor/wp-coding-standards/wpcs,/usr/src/app/vendor/yiisoft/yii2-coding-standards,/usr/src/app/vendor/magento/marketplace-eqp" && \
    chown -R app:app . && \
    rm -r ~/.composer /var/cache/misc/*

COPY . ./

RUN find -not \( -user app -and -group app \) -exec chown -R app:app {} \;

USER app

VOLUME /code

CMD ["/usr/src/app/bin/codeclimate-phpcodesniffer"]
