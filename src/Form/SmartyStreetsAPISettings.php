<?php
 /**
 *@file
 *Contains Drupal\SmartyStreetsAPI\Form\smartstreetsSettings.
 */
  namespace Drupal\SmartyStreetsAPI\Form;
  use Drupal\Core\Form\ConfigFormBase;
  use Drupal\Core\Form\FormStateInterface;
  /**
  *Class smartstreetsSettings
  *
  *@package Drupal\SmartyStreetsAPI\Form
  */
  class SmartyStreetsAPISettings extends ConfigFormBase {
    /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'SmartyStreetsAPI.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('SmartyStreetsAPI.settings');
    $form['SmartyStreetsAPI_secret_key_auth_id'] = array(
        '#type' => 'textfield',
        '#title' => t('Secret Key Auth ID'),
        '#default_value' => $config->get('SmartyStreetsAPI_secret_key_auth_id', ''),
    );
    $form['SmartyStreetsAPI_secret_auth_token'] = array(
        '#type' => 'textfield',
        '#title' => t('Secret Auth Token'),
        '#default_value' => $config->get('SmartyStreetsAPI_secret_auth_token', ''),
    );


    $form['SmartyStreetsAPI_log_api_calls'] = array(
        '#type' => 'checkbox',
        '#title' => t('Log all SmartyStreets API calls'),
        '#default_value' => $config->get('SmartyStreetsAPI_log_api_calls', ''),
    );


    $form['SmartyStreetsAPI_log_api_responses'] = array(
        '#type' => 'checkbox',
        '#title' => t('Log all SmartyStreets API responses'),
        '#default_value' => $config->get('SmartyStreetsAPI_log_api_responses', ''),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('SmartyStreetsAPI.settings')
      ->set('SmartyStreetsAPI_secret_key_auth_id', $form_state->getValue('SmartyStreetsAPI_secret_key_auth_id'))
      ->set('SmartyStreetsAPI_secret_auth_token', $form_state->getValue('SmartyStreetsAPI_secret_auth_token'))
      ->set('SmartyStreetsAPI_log_api_calls', $form_state->getValue('SmartyStreetsAPI_log_api_calls'))
      ->set('SmartyStreetsAPI_log_api_responses', $form_state->getValue('SmartyStreetsAPI_log_api_responses'))
      ->save();
  }

}
