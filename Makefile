DEBFILE=$(wildcard *.deb)

vendor:
	composer install

build: vendor
	php src/export.php
	docker build . -t lychee-package --progress plain

run:
	docker run -d -p 8080:8080 lychee-package

install:
	apt install ./$(DEBFILE)