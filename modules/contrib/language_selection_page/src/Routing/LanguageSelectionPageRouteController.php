<?php

declare(strict_types = 1);

namespace Drupal\language_selection_page\Routing;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * The LanguageSelectionPageRouteController class.
 */
final class LanguageSelectionPageRouteController implements ContainerInjectionInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * NegotiationLanguageSelectionPageForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new self(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $config = $this->configFactory->get('language_selection_page.negotiation');

    return [
      'language_selection_page' => new Route(
        $config->get('path'),
        [
          '_controller' => '\Drupal\language_selection_page\Controller\LanguageSelectionPageController::main',
          '_title' => $config->get('title'),
        ],
        [
          '_permission' => 'access content',
        ]
      ),
    ];
  }

}
