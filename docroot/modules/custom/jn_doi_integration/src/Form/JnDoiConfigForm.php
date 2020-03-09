<?php

namespace Drupal\jn_doi_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class JnDoiConfigForm extends ConfigFormBase {

  const SETTINGS = 'jn_doi_integration.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'jn_doi_integration_config_form';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['doi_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('DOI Prefix'),
      '#default_value' => $config->get('doi_prefix'),
    ];
    $form['oai_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('OAI Prefix'),
      '#default_value' => $config->get('oai_prefix'),
    ];
    $form['admin_email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Admin Email'),
      '#default_value' => $config->get('admin_email'),
    ];
    $form['verbs'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Verbs'),
      '#default_value' => $config->get('verbs'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('doi_prefix', $form_state->getValue('doi_prefix'))
      ->set('oai_prefix', $form_state->getValue('oai_prefix'))
      ->set('admin_email', $form_state->getValue('admin_email'))
      ->set('verbs', $form_state->getValue('verbs'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}