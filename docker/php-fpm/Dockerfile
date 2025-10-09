FROM php:8.2.1-fpm

ENV ACCEPT_EULA=Y

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other utilities
RUN apt-get update \
    && apt-get -y --force-yes --no-install-recommends install \
        apt-utils \
        libxml2-dev \
        gnupg \
        apt-transport-https \
        git \
        curl \
        nano \
        libzip-dev \
        libfreetype6-dev \
        libwebp-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libgmp-dev \
        libldap2-dev \
        libpq-dev \
        netcat \
        sqlite3 \
        supervisor \
        libsqlite3-dev \
        xfonts-75dpi \
        xfonts-base \
        fontconfig \
        libjpeg62-turbo \
        libxrender1 \
        libxtst6 \
        libpng16-16 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install PHP extensions
RUN docker-php-ext-install \
    gd \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    pdo_sqlite \
    zip \
    gmp \
    bcmath \
    pcntl \
    ldap \
    sysvmsg \
    exif \
    mysqli \
    intl

RUN echo "=================================================================="
RUN echo "Loaded PHP  extensions:"
RUN php -i
RUN echo "=================================================================="

# Update OpenSSL settings
RUN apt-get update -yqq \
    && apt-get install -y --force-yes --no-install-recommends openssl \
    && sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.0',g' /etc/ssl/openssl.cnf \
    && sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf \
    && rm -rf /var/lib/apt/lists/*

RUN echo "=================================================================="
RUN echo "PHP-FPM configuration:"
RUN grep '^MinProtocol' /etc/ssl/openssl.cnf
RUN grep '^CipherString' /etc/ssl/openssl.cnf
RUN echo "=================================================================="

# Install Microsoft SQL Server driver
RUN curl https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/mssql-release.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/mssql-release.gpg] https://packages.microsoft.com/debian/11/prod bullseye main" > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && apt-get -y install msodbcsql17 unixodbc-dev libodbc1 odbcinst \
    && pecl install sqlsrv \
    && pecl install pdo_sqlsrv \
    && echo "extension=pdo_sqlsrv.so" > /usr/local/etc/php/conf.d/30-pdo_sqlsrv.ini \
    && echo "extension=sqlsrv.so" > /usr/local/etc/php/conf.d/30-sqlsrv.ini \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Copy custom configuration
COPY symfony.pool.conf /usr/local/etc/

# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Set timezone
RUN echo "Asia/Jakarta" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

# Configure user permissions
RUN usermod -u 1000 www-data

# Set working directory
WORKDIR /var/www/html

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
