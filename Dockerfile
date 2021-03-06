FROM php:8.1-cli-alpine AS builder

RUN apk add git

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && mv composer.phar /bin/composer

ADD . /app
WORKDIR /app

RUN export COMPOSER_ALLOW_SUPERUSER=1 \
	&& composer update --no-cache --no-dev  \
    && php --define phar.readonly=0 /app/build/generate  \
    && chmod 770 /app/app.phar

FROM php:8.1-cli-alpine

COPY --from=builder /app/app.phar /app.phar

ENTRYPOINT /app.phar app:calc-attendance

