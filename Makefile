DEBFILE=$(wildcard *.deb)

build:
	# php src/export.php
	docker build . -t lychee-package --progress plain

run:
	docker run -d -p 8080:8080 lychee-package

install:
	apt install ./$(DEBFILE)