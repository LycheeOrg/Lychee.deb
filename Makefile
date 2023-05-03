all:
	./export.sh
	docker build . -t lychee-package --progress plain

run:
	docker run -d -p 8080:8080 lychee-package