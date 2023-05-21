<?php

namespace LycheeOrg\LycheDeb;

$root_path = dirname(__DIR__);
putenv('TMPDIR=' . $root_path . '/tmp');


include $root_path . '/vendor/autoload.php';
include 'DockerTemplate.php';

use ZipArchive;

use function Safe\chdir;
use function Safe\mkdir;
use function Safe\rename;
use function Safe\scandir;
use function Safe\system;
use function Safe\unlink;
use function Safe\file_put_contents;

// Select future version number
$version = '4.9.2';
$patch = '-1';
$is_release = count($argv) === 1;
$fetch_zip = $is_release;

$package_name = 'lychee-' . $version . $patch . '_amd64';
$package_name_deb = 'lychee-' . $version . $patch . '_amd64.deb';

// Define main paths
$path = [
	'root' => $root_path,
	'package' => $root_path . '/' . $package_name,
	'package-template' => $root_path . '/lychee',
	'package.deb' => $root_path . '/' . $package_name_deb,
	'package-web' => $root_path . '/' . $package_name . '/var/www/html/',
	'package-web-Lychee' => $root_path . '/' . $package_name . '/var/www/html/Lychee',
	'package-web-vendor' => $root_path . '/' . $package_name . '/var/www/html/Lychee/vendor',
	'zip-dir' => $root_path . '/tmp/',
	'zip' => $root_path . '/tmp/Lychee.zip',
	'Dockerfile' => $root_path . '/Dockerfile',
];

// Clean up root definition, we don't need it anymore
unset($root_path);

// We will executing a bunch of commands from here
chdir($path['root']);

// RM previous deb version
if (file_exists($path['package.deb'])) {
	unlink($path['package.deb']);
}

// RM previous package file
if (file_exists($path['package'])) {
	system('rm -fr ' . $path['package']);
}

// Copy base package and set up version number
system('cp -r ' . $path['package-template'] . ' ' . $path['package']);

// Create /var/www/html directory
if (!file_exists($path['package-web'])) {
	mkdir($path['package-web'], recursive: true);
}

// Check if temporary directory is required
if (!file_exists($path['zip-dir'])) {
	mkdir($path['zip-dir'], recursive: true);
}

$addr = 'https://github.com/LycheeOrg/Lychee/releases/latest/download/Lychee.zip';

if (!$is_release) {
	// Check if zip file exists already, download if not
	if (!file_exists($path['zip'])) {
		$addr = 'https://github.com/LycheeOrg/Lychee/archive/refs/heads/' . $argv[1] . '.zip';
		$fetch_zip = true;
	}
}

if ($fetch_zip) {
	system('wget ' . $addr . ' -O ' . $path['zip']);
}

// Unzip
$zip = new ZipArchive();
$res = $zip->open($path['zip']);
if ($res === true) {
	$zip->extractTo($path['package-web']);
	$zip->close();
} else {
	dd('Failed to extract files');
}

// clean up if release
if ($is_release) {
	unlink($path['zip']);
}

$dir = scandir($path['package-web']);

if (count($dir) < 3) {
	dd('Dist directory not found');
}

if ($dir[2] !== 'Lychee') {
	rename($path['package-web'] . '/' . $dir[2], $path['package-web-Lychee']);
}

// Check if composer install is required
if (!file_exists($path['package-web-vendor'])) {
	chdir($path['package-web-Lychee']);
	system('TMPDIR=' . $path['zip-dir'] . ' composer install', $return_var);
	if ($return_var !== 0) {
		dd('oups!');
	}
	chdir($path['root']);
}

// Backage the stuff
system("DEBEMAIL=lychee@viguier.nl DEBFULLNAME=Benoit\ Viguier dpkg-deb --build --root-owner-group " . $package_name);

// Nuke the package
// system('rm -fr ' . $path['package']);

file_put_contents($path['Dockerfile'], dockerTemplate($package_name_deb));
