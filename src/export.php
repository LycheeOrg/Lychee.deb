<?php

namespace LycheeOrg\LycheDeb;

$root_path = dirname(__DIR__);

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

// Select version
$version = '4.9.2';
$patch = '-1';

$package_name = 'lychee-' . $version . $patch . '_amd64';
$package_name_deb = 'lychee-' . $version . $patch . '_amd64.deb';

// Define main paths
$path = [
	'root' => $root_path,
	'package' => $root_path . '/' . $package_name,
	'package-template' => $root_path . '/lychee',
	'package.deb' => $root_path . '/' . $package_name_deb,
	'package-web' => $root_path . '/' . $package_name . '/var/www/html/',
	'zip' => $root_path . '/' . $package_name . '/var/www/html/Lychee.zip',
	'Dockerfile' => $root_path . '/Dockerfile',
];

// Clean up
unset($root_path);

chdir($path['root']);

// RM previous version
if (file_exists($path['package.deb'])) {
	unlink($path['package.deb']);
}

// Copy base package and set up version number
system('cp -r ' . $path['package-template'] . ' ' . $path['package']);

// Create /var/www/html directory
if (!file_exists($path['package-web'])) {
	mkdir($path['package-web'], recursive: true);
}

$addr = 'https://github.com/LycheeOrg/Lychee/releases/latest/download/Lychee.zip';
if (count($argv) !== 1) {
	$addr = 'https://github.com/LycheeOrg/Lychee/archive/refs/heads/' . $argv[1] . '.zip';
}

system('wget ' . $addr . ' -O ' . $path['zip']);

$zip = new ZipArchive();
$res = $zip->open($path['zip']);
if ($res === true) {
	$zip->extractTo($path['package-web']);
	$zip->close();
} else {
	echo 'Failed to extract files';
	exit(1);
}

// Remove Zip file before packaging
unlink($path['zip']);

$dir = scandir($path['package-web']);

if (count($dir) < 3) {
	echo 'Dist directory not found';
	exit(1);
}

if ($dir[2] !== 'Lychee');
rename($path['package-web'] . '/' . $dir[2], $path['package-web'] . '/Lychee');

// Backage the stuff
system("DEBEMAIL=lychee@viguier.nl DEBFULLNAME=Benoit\ Viguier dpkg-deb --build --root-owner-group " . $package_name);

// Nuke the package
system('rm -fr ' . $path['package']);

file_put_contents($path['Dockerfile'], dockerTemplate($package_name_deb));
