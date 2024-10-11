<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\BasicWithPublicValetDriver;

class WordPressMultisiteSubdirectoryValetDriver extends BasicWithPublicValetDriver
{
    /**
     *  Specifies the public file path of your website relative to the root of the Valet or Herd site path.
     *  For example, a default/vanilla install of WordPress in Herd or Valet, this should be left as a "/". But if your public files are located in a different directory, then specify it here, e.g. "/public".
     */
    public $rootSiteFilePath = "";

    /**
     *  Specifies the file path to the WordPress Core directory relative to the root of the Valet or Herd site path.
     *  For example, a default/vanilla install of WordPress, this should be left as a "/". But if your WordPress Core files are location in a different directory, then specify it here, e.g. "/public/wp".
     */
    public $wpCoreRootFilePath = "";

    /**
     *  Specifies the URL path used to login to WordPress. In a vanilla installation of WordPress, this should be left as an empty string. But if your URL is set differently (usually defined in the WP_SITEURL constant or within the database), then specify it here (e.g. "/wp").
     */
    public $wpSiteUrl = "";

    /**
     * Determine if the driver serves the request.
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        // Look for MULTISITE in wp-config.php. It should be there for multisite installs.
        return file_exists($sitePath . $this->rootSiteFilePath . '/wp-config.php') &&
            (strpos(file_get_contents($sitePath . $this->rootSiteFilePath . '/wp-config.php'), 'MULTISITE') !== false) &&
            (
                //Double check if we are using subdomains.
                strpos(file_get_contents($sitePath . $this->rootSiteFilePath . '/wp-config.php'), "define('SUBDOMAIN_INSTALL',false)") ||
                strpos(file_get_contents($sitePath . $this->rootSiteFilePath . '/wp-config.php'), "define('SUBDOMAIN_INSTALL', false)") ||
                strpos(file_get_contents($sitePath . $this->rootSiteFilePath . '/wp-config.php'), "define( 'SUBDOMAIN_INSTALL', false )")
            );
    }

    /**
     * Get the fully resolved path to the application's front controller.
     */
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $_SERVER['PHP_SELF']    = $uri;
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        // If URI contains one of the main WordPress directories, and it's not a request for the Network Admin,
        // drop the subdirectory segment before routing the request
        if ((stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false)) {

            if (stripos($uri, 'wp-admin/network') === false) {
                $uri = substr($uri, stripos($uri, '/wp-'));
            }

            if (!empty($this->wpCoreRootFilePath) && file_exists($sitePath . "{$this->wpCoreRootFilePath}/wp-admin")) {
                $uri = "{$this->wpSiteUrl}" . $uri;
            }
        }

        // Handle wp-cron.php properly
        if (stripos($uri, 'wp-cron.php') !== false) {
            $new_uri = substr($uri, stripos($uri, '/wp-'));

            if (file_exists($sitePath . $this->rootSiteFilePath . $new_uri)) {
                return $this->forceTrailingSlash($sitePath . $this->rootSiteFilePath . $new_uri);
            }
        }

        return parent::frontControllerPath(
            $sitePath,
            $siteName,
            $this->forceTrailingSlash($uri)
        );
    }

    /**
     * Determine if the incoming request is for a static file.
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)/*: string|false */
    {
        // If the URI contains one of the main WordPress directories and it doesn't end with a slash,
        // drop the subdirectory from the URI and check if the file exists. If it does, return the new uri.
        if (stripos($uri, 'wp-admin') !== false || stripos($uri, 'wp-content') !== false || stripos($uri, 'wp-includes') !== false) {
            if (substr($uri, -1, 1) == "/") return false;

            $new_uri = substr($uri, stripos($uri, '/wp-'));

            if (!empty($this->wpCoreRootFilePath) && file_exists($sitePath . "{$this->wpCoreRootFilePath}/wp-admin")) {
                $new_uri = "{$this->wpSiteUrl}" . $new_uri;
            }

            if (file_exists($sitePath . $this->rootSiteFilePath . $new_uri)) {
                return $this->forceTrailingSlash($sitePath . $this->rootSiteFilePath . $new_uri);
            }
        }

        return parent::isStaticFile($sitePath, $siteName, $uri);
    }

    /**
     * Redirect to uri with trailing slash.
     */
    private function forceTrailingSlash(string $uri): string
    {
        if (substr($uri, -1 * strlen('/wp-admin')) == '/wp-admin') {
            header('Location: ' . $uri . '/');
            die;
        }
        return $uri;
    }
}
