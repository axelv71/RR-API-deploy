FROM php:8.1-fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

# Install http
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions http


RUN apk --no-cache update && apk --no-cache add bash && apk --no-cache add git

RUN  apk add unzip && apk add icu-dev
RUN docker-php-ext-install intl pdo pdo_pgsql
RUN echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini
# Change upload size
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini


# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

# Install Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && apk add symfony-cli

WORKDIR /var/www/