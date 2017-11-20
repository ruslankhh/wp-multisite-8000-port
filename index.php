<?php
/**
 * Plugin Name: WP Multisite 8000 port
 * Plugin URI: https://github.com/ruslankhh/wp-multisite-8000-port
 * Description: Plugin supports multisite on a different port than :80 and :443 (e.g. :8000).
 * Version: 0.0.1
 * Author: Ruslan Khusnetdinov
 * Author URI: http://ruslankhh.com/
 * License: MIT License
 *
 * Test for multisite support on a different port than :80 and :443 (e.g. :8000)
 *
 * Here we assume that the 'siteurl' and 'home' options contain the :8000 port
 *
 * WARNING: Not suited for production sites!
 *
 * Get around the problem with wpmu_create_blog() where sanitize_user()
 * strips out the semicolon (:) in the $domain string
 * This means created sites with hostnames of
 * e.g. example.tld8000 instead of example.tld:8000
 */

if(substr(DOMAIN_CURRENT_SITE, -1, 4) == 8000){
    add_filter( 'sanitize_user', function( $username, $raw_username, $strict ) {
        // Edit the port to your needs
        $port = 8000;

        if(    $strict                                                // wpmu_create_blog uses strict mode
            && is_multisite()                                         // multisite check
            && $port == parse_url( $raw_username, PHP_URL_PORT )      // raw domain has port
            && false === strpos( $username, ':' . $port )             // stripped domain is without correct port
        )
            $username = str_replace( $port, ':' . $port, $username ); // replace e.g. example.tld8000 to example.tld:8000

        return $username;
    }, 1, 3 );

    /**
     * Temporarly change the port (e.g. :8000 ) to :80 to get around
     * the core restriction in the network.php page.
     */
    add_action( 'load-network.php', function() {
        add_filter( 'option_active_plugins', function( $value ) {
            add_filter( 'option_siteurl', function( $value ) {
                // Edit the port to your needs
                $port = 8000;

                // Network step 2
                if( is_multisite() || network_domain_check() )
                    return $value;

                // Network step 1
                static $count = 0;
                if( 0 === $count++ )
                    $value = str_replace( ':' . $port, ':80', $value );
                return $value;
            } );
            return $value;
        } );
    } );
}
