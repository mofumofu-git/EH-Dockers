FROM php:8.3-apache
ENV DEBIAN_FRONTEND=noninteractive

# Enable modules we need
RUN a2enmod auth_basic authn_file rewrite

# Copy configs
COPY conf/ports.conf /etc/apache2/ports.conf
COPY conf/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY conf/.htpasswd /etc/apache2/.htpasswd
RUN chown root:www-data /etc/apache2/.htpasswd && chmod 640 /etc/apache2/.htpasswd

# App files
COPY site/ /var/www/html/

# Install only gdb (minimal)
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends gdb; \
    apt-get install -y --no-install-recommends checksec;

# Enable python
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        -o APT::Keep-Downloaded-Packages=false \
	vim \
	socat \
	python3; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*


# Ensure dirs exist (compose will bind over them, but this keeps the image sane)
RUN mkdir -p /var/www/html/uploads /var/www/html/logs \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 0755 /var/www/html

EXPOSE 27465
