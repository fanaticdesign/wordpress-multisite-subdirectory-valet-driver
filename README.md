# WordPress Multisite Sub-directory Herd/Valet Driver

## Overview
This is a driver file to be able to have a sub-directory WordPress multisite using when using Laravel Valet or Herd. This will enable you to run a website locally have have separate multisites at https://mylocalsite.test/site-1/ and https://mylocalsite.test/site-2/, and still be able to access the WP CMS for all of the sub-directory sites as well as the multisite network.

## Prerequisites
You must be using either Valet or Herd for your local development environment:

- Laravel Valet v4+
- Laravel Herd

## Installation
Grab the `WordPressMultisiteSubdirectoryValetDriver.php` file from here.

### For Herd Users
Place the file in the following directory `~/Library/Application Support/Herd/config/valet/Drivers` and let the magic do its thing. It's then probably best to restart Herd.

### For Valet Users
Place the file in the following directory `~/.config/valet/Drivers` and let the magic do its thing. It's probably best to restart Valet.

## Configuration
Out of the box, this should support vanilla WordPress multisites that have no custom configuration. However there may be a requirement that your site's public path (relative to the root of the site in Valet or Herd) is in a different directory than usual (e.g. in /public). There may also be a requirement whereby the WordPress Core files are installed in a separate directory as well (e.g. in /public/wp).

If this is the case then please modify the values of `$wpCoreRootPath` and `$rootSitePAth` public attributes.

`$wpCoreRootPath` specifies the path to the WordPress core directory relative to the root of the Valet or Herd site path. For example, a default/vanilla install of WordPress, this should be left as a `/`. But if your WordPress Core files are location in a different directory, then specify it here, e.g. `/public/wp`.

`$rootSitePAth` specifies the public path of your website relative to the root of the Valet or Herd site path. For example, a default/vanilla install of WordPress in Herd or Valet, this should be left as a `/`. But if your public files are located in a different directory, then specify it here, e.g. `/public`.

## License
This project is licensed under the MIT License. See the [LICENSE.md](./LICENSE.md) file for more details.