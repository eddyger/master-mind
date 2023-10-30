FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y wget curl zip unzip npm

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv ~/.symfony5/bin/symfony /usr/local/bin/symfony

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer