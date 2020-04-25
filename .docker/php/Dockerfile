FROM php:7.2-fpm-alpine

RUN apk --update --no-cache add git

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions pdo_pgsql intl

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# tweak php-fpm config
RUN sed -i -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" /usr/local/etc/php/php.ini && \
    sed -i -e "s/upload_max_filesize\s*=\s*2M/upload_max_filesize = 100M/g" /usr/local/etc/php/php.ini && \
    sed -i -e "s/memory_limit = .*/memory_limit = 1024M/g" /usr/local/etc/php/php.ini && \
    sed -i -e "s/max_execution_time = .*/max_execution_time = 60/g" /usr/local/etc/php/php.ini && \
    sed -i -e "s/post_max_size\s*=\s*8M/post_max_size = 100M/g" /usr/local/etc/php/php.ini && \
    sed -i -e "s/pm = dynamic/pm = ondemand/g" /usr/local/etc/php-fpm.d/www.conf.default && \
    sed -i -e "s/pm.max_children = 5/pm.max_children = 10/g" /usr/local/etc/php-fpm.d/www.conf.default && \
    sed -i -e "s/;pm.status_path = .*/pm.status_path = \/fpm-status/g" /usr/local/etc/php-fpm.d/www.conf.default && \
    echo "date.timezone = \"Europe/Paris\"" >> /usr/local/etc/php/php.ini

WORKDIR /var/www/html

CMD php-fpm

EXPOSE 9000