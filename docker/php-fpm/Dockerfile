FROM php:8.1-fpm

WORKDIR /var/www
RUN apt-get update && apt-get install -y locales
RUN apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

RUN apt-get install -y \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
#RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd    

#RUN apt-get update && apt-get install -y nodejs npm
#RUN npm cache clean -f
#RUN npm install -g n
#RUN n stable


# Install the PHP pdo_mysql extention
#RUN docker-php-ext-install pdo pdo_mysql
RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
RUN locale-gen en_US.UTF-8
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

USER www