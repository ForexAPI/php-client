FROM php:7.4-fpm-alpine

ENV COMPOSER_HOME=/.composer
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=mlocati/php-extension-installer --link /usr/bin/install-php-extensions /usr/local/bin/
WORKDIR /app

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

RUN set -eux; \
    install-php-extensions \
    	zip \
        xdebug \
    ;

RUN mkdir -p /.composer/cache
RUN chmod -R 777 $COMPOSER_HOME
COPY --from=composer/composer:2-bin --link /composer /usr/bin/composer

CMD ["php-fpm"]