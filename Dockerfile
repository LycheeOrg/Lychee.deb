FROM debian:bookworm-slim

# Set version label
LABEL maintainer="lycheeorg"

# Install base dependencies, add user and group, clone the repo and install php libraries
RUN \
    set -ev && \
    apt-get update && \
    apt-get upgrade -qy && \
    apt-get install -qy --no-install-recommends \
    adduser

WORKDIR src/

COPY lychee-4.9.1-1_amd64.deb .

RUN apt update
RUN apt install -y ./lychee-4.9.1-1_amd64.deb

RUN chown -R www-data:www-data /var/www/html/Lychee/storage/logs && \
    chmod -R 775 /var/www/html/Lychee/storage/logs


EXPOSE 8080

CMD apachectl -D FOREGROUND
