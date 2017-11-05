<?php
/**
 * @file
 * Contains \Drupal\SmartyStreetsAPI\Controller\SmartyStreetsAPIService.
 */

namespace Drupal\SmartyStreetsAPI\Controller;
require_once(dirname(dirname(__FILE__)) . '/smartystreets-php-sdk/src/ClientBuilder.php');
require_once(dirname(dirname(__FILE__)) . '/smartystreets-php-sdk/src/US_Street/Lookup.php');
require_once(dirname(dirname(__FILE__)) . '/smartystreets-php-sdk/src/StaticCredentials.php');
use SmartyStreets\PhpSdk\Exceptions\SmartyException;
use SmartyStreets\PhpSdk\StaticCredentials;
use SmartyStreets\PhpSdk\ClientBuilder;
use SmartyStreets\PhpSdk\US_Street\Lookup;

class SmartyStreetsAPIService {

  protected $service_example_value;

  /**
   * When the service is created, set a value for the example variable.
   */
  public function __construct() {
    $this->service_example_value = 'SmartyStreetsAPI Service Response';
  }

  /**
   * Return the value of the example variable.
   */
  public function getServiceExampleValue($testvar) {
    return $this->service_example_value . " " . $testvar;
    //return self::$service_example_value;
  }

  public function LookupAddress($street_address,$city,$state) {
      $smartyauthid = \Drupal::config('SmartyStreetsAPI.settings')->get('SmartyStreetsAPI_secret_key_auth_id');
      $smartyauthtoken = \Drupal::config('SmartyStreetsAPI.settings')->get('SmartyStreetsAPI_secret_auth_token');
      $staticCredentials = new StaticCredentials($smartyauthid, $smartyauthtoken);
      $client = (new ClientBuilder($staticCredentials))
//                        ->viaProxy("http://localhost:8080", "username", "password") // uncomment this line to point to the specified proxy.
                      ->buildUsStreetApiClient();

      $lookup = new Lookup();
      $lookup->setStreet($street_address);
      $lookup->setCity($city);
      $lookup->setState($state);

      try {
          $client->sendLookup($lookup);
          return $this->displayResults($lookup);
      }
      catch (SmartyException $ex) {
        return $ex->getMessage();
      }
      catch (\Exception $ex) {
        return $ex->getMessage();
      }
      return 'SmartyStreetsAPI Service Testing Complete.';
    }

    public function displayResults(Lookup $lookup) {
        $results = $lookup->getResult();
        if (empty($results)) {
            return 'No candidates. This means the address is not valid.';
        }

        $firstCandidate = $results[0];
        return 'Address is valid. (There is at least one candidate)<br>
            ZIP Code: ' . $firstCandidate->getComponents()->getZIPCode() . '<br>
            County: ' . $firstCandidate->getMetadata()->getCountyName() . '<br>
            Latitude: ' . $firstCandidate->getMetadata()->getLatitude() . '<br>
            Longitude: ' . $firstCandidate->getMetadata()->getLongitude() . '<br>
            ';
    }

}
