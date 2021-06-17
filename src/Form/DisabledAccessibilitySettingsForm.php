<?php

namespace Drupal\disabled_accessibility\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class DisabledAccessibilitySettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames()
  {
    return [
      'disabled_accessibility.settings',
    ];
  }

  public function getFormId()
  {
    return 'disabled_accessibility';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('disabled_accessibility.settings');

    $form['allow_animation_buttons'] = [
      '#type' => 'radios',
      '#title' => $this->t('Afficher les choix de l\'animation'),
      '#options' => ['allow_animation' => $this->t('Afficher'), 'disallow_animation' => $this->t('Masquer')],
      '#default_value' => is_null($config->get('allow_animation_buttons')) ? 'allow_animation' : $config->get('allow_animation_buttons'),
      '#weight' => '0',
    ];

    $form['allow_typo_buttons'] = [
      '#type' => 'radios',
      '#title' => $this->t('Afficher les choix de typographie'),
      '#options' => ['allow_typo' => $this->t('Afficher'), 'disallow_typo' => $this->t('Masquer')],
      '#default_value' => is_null($config->get('allow_typo_buttons')) ? 'allow_typo' : $config->get('allow_typo_buttons'),
      '#weight' => '1',
    ];
    $form['allow_line_height_buttons'] = [
      '#type' => 'radios',
      '#title' => $this->t('Afficher les choix de hauteur de ligne'),
      '#options' => ['allow_line_height' => $this->t('Afficher'), 'disallow_line_height' => $this->t('Masquer')],
      '#default_value' => is_null($config->get('allow_line_height_buttons')) ? 'allow_line_height' : $config->get('allow_line_height_buttons'),
      '#weight' => '2',
    ];
    $form['allow_theme_buttons'] = [
      '#type' => 'radios',
      '#title' => $this->t('Afficher les choix du theme clair ou sombre'),
      '#options' => ['allow_theme' => $this->t('Afficher'), 'disallow_theme' => $this->t('Masquer')],
      '#default_value' => is_null($config->get('allow_theme_buttons')) ? 'allow_theme' : $config->get('allow_theme_buttons'),
      '#weight' => '3',
    ];
    $form['allow_contrast_buttons'] = [
      '#type' => 'radios',
      '#title' => $this->t('Afficher les choix du contrast'),
      '#options' => ['allow_contrast' => $this->t('Afficher'), 'disallow_contrast' => $this->t('Masquer')],
      '#default_value' => is_null($config->get('allow_contrast_buttons')) ? 'allow_contrast' : $config->get('allow_contrast_buttons'),
      '#weight' => '4',
    ];
    $form['cookie_duration_days'] = [
      '#type' => 'number',
      '#title' => $this->t('Durée du cookie en jours'),
      '#description' => $this->t('(Minimum 1 jour, maximum 365 jours)'),
      '#default_value' => is_null($config->get('cookie_duration_days')) ? 7 : $config->get('cookie_duration_days'),
      '#weight' => '5',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $values = $form_state->getValues();

    if (!is_numeric($values['cookie_duration_days'])) {
      $form_state->setError($form['cookie_duration_days'], t('La durée doit être un nombre'));
    }
    if (!ctype_digit($values['cookie_duration_days'])) {
      $form_state->setError($form['cookie_duration_days'], t('La durée doit être un chiffre rond'));
    }
    if ($values['cookie_duration_days'] > 365) {
      $form_state->setError($form['cookie_duration_days'], t('La durée ne peut excéder 365 jours'));
    }
    if ($values['cookie_duration_days'] <= 1) {
      $form_state->setError($form['cookie_duration_days'], t('La durée est de 1 jour minimum'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('disabled_accessibility.settings')
      ->set('allow_animation_buttons', $form_state->getValue('allow_animation_buttons'))
      ->set('allow_typo_buttons', $form_state->getValue('allow_typo_buttons'))
      ->set('allow_line_height_buttons', $form_state->getValue('allow_line_height_buttons'))
      ->set('allow_theme_buttons', $form_state->getValue('allow_theme_buttons'))
      ->set('allow_contrast_buttons', $form_state->getValue('allow_contrast_buttons'))
      ->set('cookie_duration_days', $form_state->getValue('cookie_duration_days'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
