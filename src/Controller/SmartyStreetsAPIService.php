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

  /**
   * When the service is created, set a value for the example variable.
   */
  public function __construct() {
    $this->service_default_value = 'SmartyStreetsAPI Service Response';
  }

  public function LookupAddress($street_address,$city,$state) {
      $config = \Drupal::config('SmartyStreetsAPI.settings');
      $smartylogapicalls = $config->get('SmartyStreetsAPI_log_api_calls');
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
          if($smartylogapicalls==1){
              \Drupal::logger('SmartyStreetsAPI')->notice('API Call: @street_address, @city, @state',
                  array(
                      '@street_address' => $street_address,
                      '@city' => $city,
                      '@state' => $state,
                  ));
          }
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
        $config = \Drupal::config('SmartyStreetsAPI.settings');
        $smartylogapiresponses = $config->get('SmartyStreetsAPI_log_api_responses');
        $results = $lookup->getResult();
        if (empty($results)) {
            if($smartylogapiresponses==1){
                \Drupal::logger('SmartyStreetsAPI')->notice('API Response: @error',
                    array(
                        '@error' => 'No candidates. This means the address is not valid.',
                    ));
            }
            return array("valid" => 0,"error" => "No candidates. This means the address is not valid.");
        }

        $firstCandidate = $results[0];
        if($smartylogapiresponses==1){
            \Drupal::logger('SmartyStreetsAPI')->notice('API Response: @zipcode, @county, @latitude, @longitude',
                array(
                    '@zipcode' => $firstCandidate->getComponents()->getZIPCode(),
                    '@county' => $firstCandidate->getMetadata()->getCountyName(),
                    '@latitude' => $firstCandidate->getMetadata()->getLatitude(),
                    '@longitude' => $firstCandidate->getMetadata()->getLongitude(),
                ));
        }
        return array("valid" => 1,"zipcode" => $firstCandidate->getComponents()->getZIPCode(),
            "county" => $firstCandidate->getMetadata()->getCountyName(),
            "latitude" => $firstCandidate->getMetadata()->getLatitude(),
            "longitude" => $firstCandidate->getMetadata()->getLongitude());
    }

}
