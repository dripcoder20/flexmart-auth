FROM php:7.4.7-fpm
RUN apt-get update -yy \
	&& apt-get install -y \
	libpng-dev \
	libxml2-dev \
	zlib1g-dev \
	libpq-dev \ 
	sqlite3 \
	libzip-dev \
	zip \
	libsqlite3-dev \ 
	imagemagick \ 
	libonig-dev \
	git \
	&& apt-get clean -y 
RUN docker-php-ext-install  \
	gd \
	soap \
	zip \
	pdo \
	pdo_mysql \
	pdo_pgsql \
	pdo_sqlite \
	xml \
	mbstring
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www
COPY . .