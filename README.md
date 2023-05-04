# Lychee.deb

**This repository provides a `.deb` package for a seamless installation of Lychee with Apache2 and SQLITE.**

<p align="center"><a href="https://lycheeorg.github.io"><img src="https://raw.githubusercontent.com/LycheeOrg/Lychee/master/Banner.png" width="400px" alt="@LycheeOrg"></a></p>

[![GitHub Release][release-shield]](https://github.com/LycheeOrg/Lychee.deb/releases)
[![MIT License][license-shield]](https://github.com/LycheeOrg/Lychee.deb/blob/master/LICENSE)
[![Downloads][download-shield]](https://github.com/LycheeOrg/Lychee.deb/releases)
<br>
[![Website][website-shield]](https://lycheeorg.github.io)
[![Documentation][docs-shield]](https://lycheeorg.github.io/docs/)
[![Changelog][changelog-shield]](https://lycheeorg.github.io/docs/releases.html)
[![Gitter][gitter-shield]](https://gitter.im/LycheeOrg/Lobby)


#### A great looking and easy-to-use photo-management-system.

_Since the 1st of April 2018 this project has moved to it's own Organisation (https://github.com/LycheeOrg) where people are able to submit their fixes to it. We, the Organisation owners, want to thank electerious (Tobias Reich) for the opportunity to make this project live on._

![Lychee](https://camo.githubusercontent.com/b9010f02c634219795950e034f511f4cf4af5c60/68747470733a2f2f732e656c6563746572696f75732e636f6d2f696d616765732f6c79636865652f312e6a706567)
![Lychee](https://camo.githubusercontent.com/5484591f0b15b6ba27d4845b292cc5d3a988b3b9/68747470733a2f2f732e656c6563746572696f75732e636f6d2f696d616765732f6c79636865652f322e6a706567)

Lychee is a free photo-management tool, which runs on your server or web-space. Installing is a matter of seconds. Upload, manage and share photos like from a native application. Lychee comes with everything you need and all your photos are stored securely. Read more on our [website](https://LycheeOrg.github.io).

## Installation

1. Download the `Lychee-<version>-<patch>_amd64.deb` file.
2. `sudo apt install Lychee-<version>-<patch>_amd64.deb`.

### What does this .deb file do ?

This debian installer provides you a setup with the followings:

- It will install `apache2` and `libapache2-mod-php8.2` (and `apt` will download the required dependency).
- It will serve Lychee on **port 8080** (which you can change later in `/etc/sites-available/001-lychee-test.conf`)
- It will set up a DB with SQLITE: no MariaDB/MySQL and migrate the database.
- It will install lychee in `/var/www/html/Lychee`

### What does this .deb file does not do?

We provide a minimal setup but we leave it to the user to securize their installation.

- It does not enable HTTPS / provide a certificate with [let's encrypt](https://letsencrypt.org/).
- It does not provides you with a domain name associated to Lychee.

## :warning: Uninstall

**This `.deb` file WILL REMOVE ALL INSTALLED FILE.**
We assume that if you are removing Lychee, it means that you are not happy with it.
For this reason, any files in `/var/www/html/Lychee` will be removed, **including uploaded pictures and database**.

## How to manually build the .deb file ?

In order to build the `.deb` file run `export.sh`

## Dockerfile & Makefile

_This requires a basic docker installation._

`.deb` files are run as root and can do a lot of damage if mistakes are made.
E.g. a `chown -R www-data:www-data .` at the wrong place can easilly break your full setup.

For this reason, in order to test the deb package there is a `Dockerfile` which allows to install
it from scratch without compromising the safety of your machine.

After creating the `.deb` use `docker build . -t lychee-package --progress plain` to build the Docker image.

Run the Docker image with the following command: `docker run -d -p 8080:8080 lychee-package`  
This will expose port 8080 on your laptop and you can verify that Lychee is properly installed.

Alternatively, you can use `make build` and `make run` to execute those commands.

## Update

It is unclear how updating works. To be explored.

## Troubleshooting

Take a look at the [Documentation](https://lycheeorg.github.io/docs/), particularly the [FAQ](https://lycheeorg.github.io/docs/faq_troubleshooting.html) if you have problems. Discovered a bug? Please create an issue [here](https://github.com/LycheeOrg/Lychee/issues) on GitHub! You can also contact us directly on [gitter (login with your github account). &#187;](https://gitter.im/LycheeOrg/Lobby)

## Open Source Community Support

<img src="https://resources.jetbrains.com/storage/products/company/brand/logos/PhpStorm_icon.png" alt="PhpStorm" width="50"/>

We would like to thank Jetbrains for supporting us with their [Open Source Development - Community Support][jetbrains-opensource] program.

[1]: https://lycheeorg.github.io/docs/settings.html
[release-shield]: https://img.shields.io/github/release/LycheeOrg/Lychee.deb.svg
[license-shield]: https://img.shields.io/github/license/LycheeOrg/Lychee.deb.svg
[website-shield]: https://img.shields.io/badge/-Website-informational.svg?logo=data:image/png;base64,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
[docs-shield]: https://img.shields.io/badge/-Documentation-informational.svg?logo=data:image/png;base64,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
[changelog-shield]: https://img.shields.io/badge/-Changelog-informational.svg?logo=data:image/png;base64,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
[gitter-shield]: https://img.shields.io/gitter/room/LycheeOrg/Lobby.svg?logo=gitter
[jetbrains-opensource]: https://www.jetbrains.com/community/opensource/
[download-shield]: https://img.shields.io/github/downloads/LycheeOrg/Lychee.deb/total