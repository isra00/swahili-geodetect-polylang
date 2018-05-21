<?php

/*
Plugin Name: Swahili Geo-detect for Polylang
Description: Overrides Polylang's language detection (based on user agent's Accept-Language header) setting the language = Swahili for users geo-located in Swahili-speaking countries, since they usually have the user-agent in English. If user does not come from a Swahili-speaking country, continue with the usual Polylang language detection logic.
Version: 0.1
Author: Israel Viana
Author URI: http://srmtanzania.org
*/

/**
 * Plug-in logic
 */
class SwahiliGeodetect {

	protected $config = [
		'geo-db-file'		=> __DIR__ . '/GeoLite2-Country.mmdb',
		'swahili-countries' => ['TZ', 'KE'],
		'swahili-code'		=> 'sw',
	];

	public static $urlPattern = '/(.*)\/([a-z]{2})(\/?)$/i';

	/**
	 * Main logic. It is called as a filter that receives the redirection given 
	 * by Polylang's auto-detection. If it is not a swahili-speaking country or
	 * something has failed, do nothing = Polylang's redirection will be kept.
	 */
	function getGeoDetectedUrl($redirectUrl, $languageFromPolylang)
	{
		// English has not been detected by Polylang => Swahili country but user 
		// chose a different language (cookie!).
		if ($languageFromPolylang != 'en')
		{
			return $redirectUrl;
		}

		include_once __DIR__ . '/vendor/autoload.php';
		$geoip = new \GeoIp2\Database\Reader($this->config['geo-db-file']);

		try
		{
			$record = $geoip->country($this->getClientIp());

			if (false !== array_search($record->country->isoCode, $this->config['swahili-countries']))
			{
				return preg_replace(self::$urlPattern, '$1/' . $this->config['swahili-code'] . '$3', $redirectUrl);
			}
		}
		catch (\GeoIp2\Exception\AddressNotFoundException $e)
		{
			return $redirectUrl;
		}
	}

	public function getClientIp()
	{
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		//return '89.128.196.164'; //vigo
		return '196.44.161.193'; //tz
		return $ip;
	}
}


/*
 * Plugin procedure
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'polylang/polylang.php' ) ) 
{
	add_action( 'pll_redirect_home', function($url) 
	{
		if (preg_match(SwahiliGeodetect::$urlPattern, $url, $lang))
		{
			$geoDetect = new SwahiliGeodetect;
			return $geoDetect->getGeoDetectedUrl($url, $lang[2]);
		}

		return $url;
	});
}