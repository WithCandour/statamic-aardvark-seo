FROM php:7.2-apache

# Install Required Depedencies, NodeJS & BuildEssentials
RUN apt-get update && apt-get install -y --force-yes \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  imagemagick \
  libmagickwand-dev \
  libexif-dev \
  ssl-cert \
  build-essential \
  libzip-dev \
  unzip \
  wget

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install -j$(nproc) zip \
  && docker-php-ext-install -j$(nproc) pdo \
  && docker-php-ext-install -j$(nproc) pdo_mysql \
  && docker-php-ext-install -j$(nproc) bcmath \
  && pecl install imagick  \
  && docker-php-ext-enable imagick \
  && docker-php-ext-install -j$(nproc) exif

ENV COMPOSER_VERSION 1.8.4
ENV COMPOSER_CHECKSUM 1722826c8fbeaf2d6cdd31c9c9af38694d6383a0f2bf476fe6bbd30939de058a
RUN wget -q https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar && \
    echo "$COMPOSER_CHECKSUM  composer.phar" | sha256sum -c - && \
    mv composer.phar /usr/bin/composer && \
    chmod +x /usr/bin/composer
ENV PATH="${PATH}:/root/.composer/vendor/bin"

ENV CODESNIFFER_VERSION 3.4.2
RUN composer -q global require "squizlabs/php_codesniffer=$CODESNIFFER_VERSION" && \
    phpcs --version

ENV STATAMIC_VERSION 2.11.10
ENV STATAMIC_CHECKSUM c2a87267ee63623478ea047336945b4f1caa1cb51b803d713cc7c8cf69158782
RUN wget -q https://outpost.statamic.com/v2/get/$STATAMIC_VERSION -O statamic-$STATAMIC_VERSION.zip && \
    echo "$STATAMIC_CHECKSUM  statamic-$STATAMIC_VERSION.zip" | sha256sum -c - && \
    unzip -q statamic-$STATAMIC_VERSION.zip -d /tmp/ && \
    mv /tmp/statamic/* /var/www/html && \
    rm statamic-$STATAMIC_VERSION.zip

WORKDIR /var/www/html
EXPOSE 3000

ENTRYPOINT ["php"]
CMD ["-d", "memory_limit=512M", "-S", "0.0.0.0:3000", "/var/www/html/statamic/server.php"]