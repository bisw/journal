<?php

namespace Drupal\journal\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class JournalConfigForm extends ConfigFormBase {
    /** @var string Config settings */
  const SETTINGS = 'journal.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'journal_config_form';
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

    $form['journal'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Journal'),
      '#default_value' => $config->get('journal'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      // Retrieve the configuration
       $this->configFactory->getEditable(static::SETTINGS)
      ->set('journal', $form_state->getValue('journal'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
