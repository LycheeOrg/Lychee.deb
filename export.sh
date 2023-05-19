#!/usr/bin/bash

# Export packaging variables
export DEBEMAIL="lychee@viguier.nl"
export DEBFULLNAME="Benoit Viguier"

# Select version
VERSION="4.9.1"
PATCH="-1"

# RM previous version
rm lychee-${VERSION}${PATCH}_amd64.deb 2>/dev/null || true

# Copy base package and set up version number
cp -r lychee lychee-${VERSION}${PATCH}_amd64

# Create /var/www/html directory
mkdir -p lychee-${VERSION}${PATCH}_amd64/var/www/html/

# Get into /var/www/html directory
cd lychee-${VERSION}${PATCH}_amd64/var/www/html/

# Get latest release
wget https://github.com/LycheeOrg/Lychee/releases/download/v${VERSION}/Lychee.zip
unzip Lychee.zip

# Remove Zip file before packaging
rm Lychee.zip

# Go back a few steps
cd ../../../..

# Verify directory
pwd

# Backage the stuff
dpkg-deb --build --root-owner-group lychee-${VERSION}${PATCH}_amd64

# Nuke the package
rm -fr lychee-${VERSION}${PATCH}_amd64
