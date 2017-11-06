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

  protected $service_default_value;

  public function __construct() {
    $this->service_default_value = 'SmartyStreetsAPI Service Response';
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
        return array("valid" => 0,"error" => $ex->getMessage());
      }
      catch (\Exception $ex) {
        return array("valid" => 0,"error" => $ex->getMessage());
      }
      return array("valid" => 0,"error" =>"Unidentified error. Something went wrong with SmartyStreetsAPI.");
    }

    public function displayResults(Lookup $lookup) {
        $results = $lookup->getResult();
        if (empty($results)) {
            return array("valid" => 0,"error" => "No candidates. This means the address is not valid.");
        }

        $firstCandidate = $results[0];
        return array("valid" => 1,"zipcode" => $firstCandidate->getComponents()->getZIPCode(),
            "county" => $firstCandidate->getMetadata()->getCountyName(),
            "latitude" => $firstCandidate->getMetadata()->getLatitude(),
            "longitude" => $firstCandidate->getMetadata()->getLongitude());

    }

}
