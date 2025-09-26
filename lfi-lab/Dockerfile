# ./Dockerfile
FROM php:8.3-apache

# Serve the lab subfolder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/lfi-lab
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && a2enmod rewrite

# Run PHP as user1 (UID 1001) so your webshell is not root
RUN useradd -m -u 1001 -s /usr/sbin/nologin user1 \
 && sed -ri 's/^[# ]*User .*/User user1/; s/^[# ]*Group .*/Group user1/' /etc/apache2/apache2.conf

# Ensure the logs dir exists (a bind will overlay the file)
RUN mkdir -p /var/www/html/lfi-lab/logs

# common PHP extensions
#RUN docker-php-ext-install 
#mysqli pdo pdo_mysql


