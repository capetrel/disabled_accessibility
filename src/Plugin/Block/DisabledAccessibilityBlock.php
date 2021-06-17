<?php

namespace Drupal\disabled_accessibility\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\disabled_accessibility\Form\DisabledAccessibilityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for accessibility in a block.
 *
 * @Block(
 *  id = "disabled_accessibility_block",
 *  admin_label = @Translation("Disabled Accessibility Block"),
 * )
 */
class DisabledAccessibilityBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder.
   *
   * @var FormBuilderInterface
   */
  protected FormBuilderInterface $formBuilder;

  /**
   * Constructs a new Block.
   *
   * @param array $configuration A configuration array containing information about the plugin instance.
   * @param string $plugin_id The plugin ID for the plugin instance.
   * @param mixed $plugin_definition The plugin implementation definition.
   * @param FormBuilderInterface $form_builder The form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * Builds the donation form.
   *
   * @return array A render array.
   */
  public function build() {
    return $this->formBuilder->getForm(EfilAccessibilityForm::class);
  }

}
