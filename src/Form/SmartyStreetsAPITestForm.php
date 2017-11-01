<?php
 /**
 *@file
 *Contains Drupal\SmartyStreetsAPI\Form\SmartyStreetsAPITestForm.
 */
  namespace Drupal\SmartyStreetsAPI\Form;
  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  /**
  *Class smartstreetsSettings
  *
  *@package Drupal\SmartyStreetsAPI\Form
  */
  class SmartyStreetsAPITestForm extends FormBase {
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
    return 'test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['SmartyStreetsAPI_street_address'] = array(
        '#type' => 'textfield',
        '#title' => t('Street Addres'),
        '#default_value' => t(''),
    );
    $form['SmartyStreetsAPI_city'] = array(
        '#type' => 'textfield',
        '#title' => t('City'),
        '#default_value' => t(''),
    );
    $form['SmartyStreetsAPI_state'] = array(
        '#type' => 'textfield',
        '#title' => t('State'),
        '#default_value' => t(''),
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    );
    return $form;
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
    //drupal_set_message($this->t('Your street address is @street', array('@street' => $form_state->getValue('SmartyStreetsAPI_street_address'))));
    $_SESSION['street_address'] = $form_state->getValue('SmartyStreetsAPI_street_address');
    $_SESSION['city'] = $form_state->getValue('SmartyStreetsAPI_city');
    $_SESSION['state'] = $form_state->getValue('SmartyStreetsAPI_state');
    $form_state->setRedirect('SmartyStreetsAPI.validate');
    return;
  }

}
