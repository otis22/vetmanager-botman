FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install gcc make libc6-dev wget nano php7.4-redis php7.4-xdebug php7.4-intl php7.4-mysql mysql-client php7.4-bcmath \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install redis cli

RUN cd /tmp/ \
    && wget http://download.redis.io/redis-stable.tar.gz \
    && tar xvzf redis-stable.tar.gz \
    && cd redis-stable \
    && make redis-cli \
    && cp src/redis-cli /usr/local/bin/ \
    && rm -rf /tmp/*

RUN chown -R www-data:www-data /application
