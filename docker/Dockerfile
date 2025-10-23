FROM php:8.1.0-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    wget \
    git zip unzip \
    sudo

RUN wget https://github.com/emcrisostomo/fswatch/releases/download/1.9.3/fswatch-1.9.3.tar.gz \
    && tar -zxvf fswatch-1.9.3.tar.gz \
    && cd fswatch-1.9.3 \
    && ./configure \
    && make \
    && make install \
    && ldconfig

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

ADD ./ /var/www
WORKDIR /var/www

CMD ["php", "-a"]
