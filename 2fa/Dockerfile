FROM php:8.3-apache

# Enable modules needed
RUN a2enmod auth_basic authn_file rewrite

# Copy configs
COPY conf/ports.conf /etc/apache2/ports.conf
COPY conf/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY conf/.htpasswd /etc/apache2/.htpasswd
RUN chown root:www-data /etc/apache2/.htpasswd && chmod 640 /etc/apache2/.htpasswd

# App files
COPY site/ /var/www/html/

# Enable python
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        -o APT::Keep-Downloaded-Packages=false \
        vim \
        socat; \
	apt-get install -y --no-install-recommends checksec; \ 
	apt-get install -y --no-install-recommends gdb;



# Install only gdb (minimal)
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends gdb;

# Install Python + pwntools + its runtime deps, then remove git/pip
RUN set -eux; \
  apt-get update; \
  apt-get install -y --no-install-recommends \
      python3 python3-pip \
      libffi8 libssl3 \
      git \
      ncurses-term \
  && python3 -m pip install --no-cache-dir --no-compile --break-system-packages pwntools \
  && apt-get install -y --no-install-recommends python3-packaging \
  && apt-get purge -y git python3-pip \
  && apt-get autoremove -y --purge \
  && rm -rf /usr/lib/python3*/ensurepip /usr/local/lib/python3*/ensurepip \
            /var/lib/apt/lists/* /root/.cache


# make pwntools visible to every user (www-data, etc.)
ENV PYTHONPATH=/opt/pwntools:$PYTHONPATH
ENV TERM=xterm

# Ensure dirs exist
RUN mkdir -p /var/www/html/tmp  \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 0755 /var/www/html 

EXPOSE 65530
