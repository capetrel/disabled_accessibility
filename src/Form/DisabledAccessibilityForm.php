<?php

namespace Drupal\disabled_accessibility\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DisabledAccessibilityForm extends FormBase {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    $instance = parent::create($container);
    $instance->configFactory = $container->get('config.factory');
    return $instance;
  }

  public function getFormId()
  {
    return 'disabled_accessibility_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $config = $this->configFactory->get('disabled_accessibility.settings');

    $animation_btn = $config->get('allow_animation_buttons');
    $typo_btn = $config->get('allow_typo_buttons');
    $theme_btn = $config->get('allow_theme_buttons');
    $line_height_btn = $config->get('allow_line_height_buttons');
    $contrast_btn = $config->get('allow_contrast_buttons');

    if ( $animation_btn === 'disallow_animation'
      && $typo_btn === 'disallow_typo'
      && $theme_btn === 'disallow_theme'
      && $line_height_btn === 'disallow_line_height'
      && $contrast_btn === 'disallow_contrast' ) {

      $form['no_buttons'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#prefix' => '<div class="accessibility-no-buttons">',
        '#value' => $this->t('Aucun boutons activés'),
        '#suffix' => '</div>',
      ];

    } else {

      if ( $animation_btn === 'allow_animation') {
        $form['anime'] = [
          '#type' => 'radios',
          '#title' => $this->t('Animations'),
          '#options' => ['default_anime' => $this->t('Activé'), 'deactive_anime' => $this->t('Désactivées')],
          '#weight' => '1',
        ];
      }
      if ($typo_btn === 'allow_typo' ){
        $form['typo'] = [
          '#type' => 'radios',
          '#title' => $this->t('Police (dyslexie)'),
          '#options' => ['default_typo' => $this->t('Défaut'), 'adapt_typo' => $this->t('Adapté')],
          '#weight' => '2',
        ];
      }
      if ( $line_height_btn === 'allow_line_height') {
        $form['line_height'] = [
          '#type' => 'radios',
          '#title' => $this->t('Interlignage'),
          '#options' => ['default_line_height' => $this->t('Défaut'), 'high_line_height' => $this->t('Augmenté')],
          '#weight' => '3',
        ];
      }
      if ( $theme_btn === 'allow_theme') {
        $form['theme'] = [
          '#type' => 'radios',
          '#title' => $this->t('Thème'),
          '#options' => ['default_theme' => $this->t('Défaut'), 'dark_theme' => $this->t('Sombre')],
          '#weight' => '4',
        ];
      }
      if ( $contrast_btn === 'allow_contrast') {
        $form['contrast'] = [
          '#type' => 'radios',
          '#title' => $this->t('Contrastes'),
          '#options' => ['default_contrast' => $this->t('Défaut'), 'enforce_contrast' => $this->t('Renforcé'), 'inverse_contrast' => $this->t('Inversé')],
          '#weight' => '5',
        ];
      }

      $cookie_duration = $anim_btn = $config->get('cookie_duration_days');
      $form['#attached']['library'][] = 'disabled_accessibility/form';
      $form['#attached']['drupalSettings']['disabledAccessibilityCookieDuration'] = $cookie_duration;

    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state){}
}
