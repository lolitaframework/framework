<?php
namespace lolita\LolitaFramework\Geoip;

use \lolita\LolitaFramework\Core\Validation;
use \lolita\LolitaFramework\Core\Loc;
use GeoIp2\Database\Reader;

class Geoip
{

    /**
     * Class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        require_once 'vendor/autoload.php';
    }

    /**
     * Get info from ip address
     *
     * @param  string $ip
     * @return array
     */
    public static function info($ip)
    {
        if(!Validation::ip($ip)) {
            return null;
        }
        // This creates the Reader object, which should be reused across
        // lookups.
        $reader = new Reader(__DIR__ . DS . 'GeoLite2-City.mmdb');
        // Replace "city" with the appropriate method for your database, e.g.,
        // "country".
        $record = $reader->city($ip);
        return $record->jsonSerialize();
    }
}
