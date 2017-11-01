<?php
/**
 * @file
 * Contains \Drupal\SmartyStreetsAPI\Controller\SmartyStreetsAPIValidate.
 */
namespace Drupal\SmartyStreetsAPI\Controller;
$output = "";

require_once(dirname(dirname(__FILE__)) . '/smartystreets-php-sdk/src/ClientBuilder.php');
require_once(dirname(dirname(__FILE__)) . '/smartystreets-php-sdk/src/US_Street/Lookup.php');
require_once(dirname(dirname(__FILE__)) . '/smartystreets-php-sdk/src/StaticCredentials.php');
use SmartyStreets\PhpSdk\Exceptions\SmartyException;
use SmartyStreets\PhpSdk\StaticCredentials;
use SmartyStreets\PhpSdk\ClientBuilder;
use SmartyStreets\PhpSdk\US_Street\Lookup;
use Drupal\Core\Controller\ControllerBase;


class SmartyStreetsAPIValidate extends ControllerBase {
  public function LookupAddress() {
      $smartyauthid = \Drupal::config('SmartyStreetsAPI.settings')->get('SmartyStreetsAPI_secret_key_auth_id');
      $smartyauthtoken = \Drupal::config('SmartyStreetsAPI.settings')->get('SmartyStreetsAPI_secret_auth_token');
      $staticCredentials = new StaticCredentials($smartyauthid, $smartyauthtoken);
      $client = (new ClientBuilder($staticCredentials))
//                        ->viaProxy("http://localhost:8080", "username", "password") // uncomment this line to point to the specified proxy.
                      ->buildUsStreetApiClient();

      $lookup = new Lookup();
      $lookup->setStreet($_SESSION['street_address']);
      $lookup->setCity($_SESSION['city']);
      $lookup->setState($_SESSION['state']);

      try {
          $client->sendLookup($lookup);
          return $this->displayResults($lookup);
      }
      catch (SmartyException $ex) {
        return array(
            '#markup' => '' . t($ex->getMessage()) . '',
        );
      }
      catch (\Exception $ex) {
        return array(
            '#markup' => '' . t($ex->getMessage()) . '',
        );
      }
      return array(
          '#markup' => '' . t('SmartyStreetsAPI Testing Complete.') . '',
      );
    }

  public function displayResults(Lookup $lookup) {
      $results = $lookup->getResult();
      if (empty($results)) {
          return array(
              '#markup' => '' . t('No candidates. This means the address is not valid.') . '',
          );
      }

      $firstCandidate = $results[0];
      return array(
          '#markup' => '' . t('Address is valid. (There is at least one candidate)<br>
          ZIP Code: ' . $firstCandidate->getComponents()->getZIPCode() . '<br>
          County: ' . $firstCandidate->getMetadata()->getCountyName() . '<br>
          Latitude: ' . $firstCandidate->getMetadata()->getLatitude() . '<br>
          Longitude: ' . $firstCandidate->getMetadata()->getLongitude() . '<br>
          ') . '',
      );

  }
}
