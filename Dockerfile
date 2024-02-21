FROM composer AS stage_composer
ARG COMPOSER_AUTH
ENV COMPOSER_AUTH ${COMPOSER_AUTH}

WORKDIR /vendor
COPY ./composer.json ./
COPY ./composer.lock ./

RUN composer install --ignore-platform-reqs --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts  --no-dev
RUN rm -rf /root/.composer

FROM node:18-alpine as node
WORKDIR /build
COPY ./ ./
RUN npm i && npm run build:prod

FROM php:7.4-fpm as production
WORKDIR /project
ARG UID=1000
ARG PHP_FPM_INI_DIR="/usr/local/etc/php"
COPY .docker/conf.d/php.ini $PHP_FPM_INI_DIR/conf.d/php.ini

RUN apt-get update
RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
      	libzip-dev \
      	libxml2-dev \
        libpq-dev \
      	libcurl4-openssl-dev \
        nginx \
      	zip unzip curl git vim libgconf-2-4 libatk1.0-0 libatk-bridge2.0-0 libgdk-pixbuf2.0-0 libgtk-3-0 libgbm-dev libnss3-dev libxss-dev libx11-xcb1 libasound2
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd zip xml opcache curl iconv intl json mysqli pdo pdo_mysql pdo_pgsql pgsql sockets

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

RUN curl -LO http://dl.google.com/linux/chrome/deb/pool/main/g/google-chrome-stable/google-chrome-stable_121.0.6167.184-1_amd64.deb && \
    apt-get install -y ./google-chrome-stable_121.0.6167.184-1_amd64.deb && \
    rm google-chrome-stable_121.0.6167.184-1_amd64.deb

RUN rm -rf /var/lib/apt/lists/*

ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION v18.17.0
RUN mkdir -p $NVM_DIR
RUN curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.1/install.sh | bash
RUN /bin/bash -c "source $NVM_DIR/nvm.sh && nvm install $NODE_VERSION && nvm use --delete-prefix $NODE_VERSION"
ENV NODE_PATH $NVM_DIR/versions/node/$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/versions/node/$NODE_VERSION/bin:$PATH

# download tini
ARG TINI_VERSION='v0.19.0'
ADD https://github.com/krallin/tini/releases/download/${TINI_VERSION}/tini /usr/local/bin/tini
RUN chmod +x /usr/local/bin/tini

COPY --from=stage_composer /vendor ./
COPY . ./
RUN rm -rf lib/MBMigration/Builder/Layout/Theme/*/Assets/*
COPY --from=node /build/lib/MBMigration/Builder/Layout/Theme/*/Assets/dist ./

COPY .docker/nginx/nginx.conf /etc/nginx/sites-enabled/default
COPY .docker/entrypoint.sh /usr/local/bin/docker-entrypoint

RUN mkdir -p var/log && mkdir -p var/cache
RUN chown -R www-data:www-data var/log && chown -R www-data:www-data var/cache

ENTRYPOINT ["tini", "docker-entrypoint", "--"]

CMD []

FROM production as development
COPY --from=stage_composer /usr/bin/composer /usr/bin/composer

ARG PHP_FPM_INI_DIR="/usr/local/etc/php"

RUN pecl install xdebug-3.1.5 && docker-php-ext-enable xdebug
COPY .docker/conf.d/xdebug.ini "${PHP_FPM_INI_DIR}/conf.d/xdebug.ini"
