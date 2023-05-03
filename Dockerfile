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

COPY lychee-4.7.3-1_amd64.deb .

RUN apt install -y ./lychee-4.7.3-1_amd64.deb

EXPOSE 8080

CMD apachectl -D FOREGROUND
