<?php
/**
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev/wordpress-plugins/optimize-analytics-wordpress/
 * @copyright: (c) 2019 Daan van den Bergh
 * @license  : GPL2v2 or later
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Remote file to download
$remoteFile = CAOS_REMOTE_URL . '/' . CAOS_OPT_REMOTE_JS_FILE;
$localFile  = CAOS_LOCAL_FILE_DIR;

// Check if directory exists, otherwise create it.
$uploadDir = CAOS_LOCAL_DIR;
if (!file_exists($uploadDir)) {
    wp_mkdir_p($uploadDir);
}

if (CAOS_OPT_REMOTE_JS_FILE == 'gtag.js') {
    $remoteFile = array(
        'analytics' => array(
            'remote' => CAOS_GA_URL . '/analytics.js',
            'local'  => CAOS_LOCAL_DIR . 'analytics.js'
        ),
        'gtag' => array(
            'remote' => CAOS_GTM_URL . '/' . CAOS_OPT_REMOTE_JS_FILE,
            'local'  => CAOS_LOCAL_FILE_DIR
        )
    );
}

if (is_array($remoteFile)) {
    foreach ($remoteFile as $file => $location) {
        caos_analytics_update($location['remote'], $location['local']);

        if ($file == 'gtag') {
            caos_analytics_update_gtag_js($location['local']);
        }
    }
} else {
    caos_analytics_update($remoteFile, $localFile);
    if (CAOS_OPT_STEALTH_MODE && (CAOS_OPT_REMOTE_JS_FILE == 'analytics.js')) {
        caos_analytics_insert_proxy(CAOS_LOCAL_FILE_DIR);
    }
}

/**
 * Downloads $remoteFile and writes it to $localFile
 *
 * @param $remoteFile
 * @param $localFile
 */
function caos_analytics_update($remoteFile, $localFile)
{
    // Connection time out
    $connTimeout = 10;
    $url         = parse_url($remoteFile);
    $host        = $url['host'];
    $path        = isset($url['path']) ? $url['path'] : '/';

    if (isset($url['query'])) {
        $path .= '?' . $url['query'];
    }

    $port = isset($url['port']) ? $url['port'] : '80';
    $fp   = @fsockopen($host, $port, $errno, $errstr, $connTimeout);

    if (!$fp) {
        // On connection failure return the cached file (if it exist)
        if (file_exists($localFile)) {
            readfile($localFile);
        }
    } else {
        // Send the header information
        $header = "GET $path HTTP/1.0\r\n";
        $header .= "Host: $host\r\n";
        $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6\r\n";
        $header .= "Accept: */*\r\n";
        $header .= "Accept-Language: en-us,en;q=0.5\r\n";
        $header .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
        $header .= "Keep-Alive: 300\r\n";
        $header .= "Connection: keep-alive\r\n";
        $header .= "Referer: http://$host\r\n\r\n";
        fputs($fp, $header);
        $response = '';

        // Get the response from the remote server
        while ($line = fread($fp, 4096)) {
            $response .= $line;
        }

        // Close the connection
        fclose($fp);

        // Remove the headers
        $pos      = strpos($response, "\r\n\r\n");
        $response = substr($response, $pos + 4);

        // Return the processed response
        echo $response;

        // Save the response to the local file
        if (!file_exists($localFile)) {

            // Try to create the file, if doesn't exist
            fopen($localFile, 'w');
        }

        if (is_writable($localFile)) {
            if ($fp = fopen($localFile, 'w')) {
                fwrite($fp, $response);
                fclose($fp);
            }
        }
    }
}

/**
 * Opens $file and replaces $gaUrl with $caosGaUrl
 *
 * @param $file
 */
function caos_analytics_update_gtag_js($file)
{
    $caosGaUrl = str_replace('gtag.js', 'analytics.js', caos_analytics_get_url());
    $gaUrl = CAOS_GA_URL . '/analytics.js';

    file_put_contents($file, str_replace($gaUrl, $caosGaUrl, file_get_contents($file)));
}

/**
 * Opens file and replaces every instance of google-analytics.com with CAOS' proxy endpoint.
 * Used only when Stealth Mode is enabled.
 *
 * @param $file
 */
function caos_analytics_insert_proxy($file)
{
    $find = array( 'http://', 'https://' );
    $replace = '';
    $siteUrl = str_replace( $find, $replace, get_site_url(CAOS_BLOG_ID));
    $proxyUrl = $siteUrl . CAOS_PROXY_URI;

    file_put_contents($file, str_replace('www.google-analytics.com', $proxyUrl, file_get_contents($file)));
}
