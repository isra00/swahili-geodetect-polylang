This is a Wordpress plug-in that overrides Polylang's default language detection (based on user agent's Accept-Language header) setting the language = Swahili for users geo-located in Swahili-speaking countries, since they usually have the user-agent in English. If the user does not come from a Swahili-speaking country, continue with the usual Polylang language detection logic.

## How to install

 1. Clone this repo into `wp-content/plugins/swahili-geodetect-polylang`.
 2. Run `composer install`.
 3. Download MaxMind's GeoLite2 Country database and name it `GeoLite2-Country.mmdb`. To download it you need a MaxMind API Key, then you can head to a link like this: `https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key={your_maxmind_license_key}&suffix=tar.gz`.

## FAQ

#### Q: Will this plugin be of any use if I don't use Polylang?
A: No

#### Q: Will this plugin be of any use if I don't expect or care about Swahili speakers?
A: No

#### Q: Which countries are detected as Swahili speakers?
A: Kenya and Tanzania. If you want me to include Congo, please open an issue asking for it.

#### Q: Why is `GeoLite2-Country.mmdb` not included in the repo?
A: its license does not allow to redistribute the files.

#### Q: Why don't you distribute this plugin through Wordpress official plugin repository?
A: it would be pointless given the complexity of the installation.
