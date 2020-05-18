FROM php:7.4.2-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    wget \
    sudo

RUN wget https://github.com/emcrisostomo/fswatch/releases/download/1.9.3/fswatch-1.9.3.tar.gz \
    && tar -zxvf fswatch-1.9.3.tar.gz \
    && cd fswatch-1.9.3 \
    && ./configure \
    && make \
    && make install \
    && ldconfig

ADD ./ /var/www
WORKDIR /var/www

CMD ["php", "-a"]
