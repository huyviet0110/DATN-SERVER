FROM php:8.1.10-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
  libzip-dev \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  sudo \
  && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer config -g process-timeout 3600

RUN chown -R www-data:www-data /var/www/html
EXPOSE 9000

CMD ["php-fpm"]
