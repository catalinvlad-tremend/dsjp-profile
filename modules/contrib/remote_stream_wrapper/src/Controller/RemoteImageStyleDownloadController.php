<?php

namespace Drupal\remote_stream_wrapper\Controller;

use Drupal\image\Controller\ImageStyleDownloadController;
use Drupal\image\ImageStyleInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;

/**
 * Defines a controller to serve image styles.
 */
class RemoteImageStyleDownloadController extends ImageStyleDownloadController {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $fileStorage;

  /**
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
  protected $streamWrapperManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->fileStorage = $container->get('entity_type.manager')->getStorage('file');
    $instance->streamWrapperManager = $container->get('stream_wrapper_manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function deliver(Request $request, $scheme, ImageStyleInterface $image_style) {
    // Only allow the remote files that exist in {file_managed} to have image
    // style derivatives generated. Otherwise this could open up the site to
    // allowing any remote file to be processed.
    $target = $request->query->get('file');
    $image_uri = $scheme . '://' . $target;
    /** @var \Drupal\remote_stream_wrapper\StreamWrapper\RemoteStreamWrapperInterface $wrapper */
    $wrapper = $this->streamWrapperManager->getViaScheme($scheme);
    if ($wrapper && $wrapper->preventUnmanagedFileImageStyleGeneration() && !$this->fileStorage->loadByProperties(['uri' => $image_uri])) {
      throw new AccessDeniedHttpException();
    }

    return parent::deliver($request, $scheme, $image_style);
  }

}
