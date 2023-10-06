FROM php:7.4-fpm
SHELL ["/bin/bash", "-c"]

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    g++ \
    autoconf \
    make \
    nano

# Php soap
RUN docker-php-ext-install soap

# Add node / NPM to the container
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs \
    && curl -L https://www.npmjs.com/install.sh | sh

# xdebug with VSCODE
ENV XDEBUG_VERSION=2.9.2
ENV PHP_IDE_CONFIG="serverName=fabric"
RUN pecl install xdebug-${XDEBUG_VERSION} && \
    docker-php-ext-enable xdebug && \
    rm -r /tmp/pear/* && \
    echo -e "xdebug.remote_enable=1\n\
        xdebug.remote_autostart=1\n\
        xdebug.remote_connect_back=0\n\
        xdebug.remote_port=9000\n\
        xdebug.idekey=\"VSCODE\"\n\
        xdebug.remote_log=/var/www/xdebug.log\n\
        xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd soap

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user

# CMD php artisan migrate





