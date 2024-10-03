# WordPress Multisite Sub-directory Herd/Valet Driver

## Overview
This is a driver file to be able to have a sub-directory WordPress multisite using when using Laravel Valet or Herd. This will enable you to run a website locally have have separate multisites at https://mylocalsite.test/site-1/ and https://mylocalsite.test/site-2/, and still be able to access the WP CMS for all of the sub-directory sites as well as the multisite network.

## Prerequisites
You must be using either Valet or Herd for your local development environment:

- Laravel Valet v4+
- Laravel Herd

**_⚠️ This also assumes that the root of the repo of your local WordPress website is sat inside `/public` and assumes that WordPress Core is installed inside `/public/wp/`. If this is not the case, then this will likely not work and the driver will need further modification._**

## Installation
Grab the `WordPressMultisiteSubdirectoryValetDriver.php` file from here.

### For Herd Users
Place the file in the following directory `~/Library/Application Support/Herd/config/valet/Drivers` and let the magic do its thing. It's then probably best to restart Herd.

### For Valet Users
Place the file in the following directory `~/.config/valet/Drivers` and let the magic do its thing. It's probably best to restart Valet.
