<?php

/**
 * @group pholio
 */
final class PholioMockImagesView extends AphrontView {

  private $mock;
  private $imageID;
  private $requestURI;
  private $commentFormID;

  public function setCommentFormID($comment_form_id) {
    $this->commentFormID = $comment_form_id;
    return $this;
  }

  public function getCommentFormID() {
    return $this->commentFormID;
  }

  public function setRequestURI(PhutilURI $request_uri) {
    $this->requestURI = $request_uri;
    return $this;
  }

  public function getRequestURI() {
    return $this->requestURI;
  }

  public function setImageID($image_id) {
    $this->imageID = $image_id;
    return $this;
  }

  public function getImageID() {
    return $this->imageID;
  }

  public function setMock(PholioMock $mock) {
    $this->mock = $mock;
    return $this;
  }

  public function render() {
    if (!$this->mock) {
      throw new Exception('Call setMock() before render()!');
    }

    $mock = $this->mock;

    require_celerity_resource('javelin-behavior-pholio-mock-view');

    $images = array();
    $panel_id = celerity_generate_unique_node_id();
    $viewport_id = celerity_generate_unique_node_id();

    $ids = mpull($mock->getImages(), 'getID');
    if ($this->imageID && isset($ids[$this->imageID])) {
      $selected_id = $this->imageID;
    } else {
      $selected_id = head_key($ids);
    }

    foreach ($mock->getAllImages() as $image) {
      $file = $image->getFile();
      $metadata = $file->getMetadata();
      $x = idx($metadata, PhabricatorFile::METADATA_IMAGE_WIDTH);
      $y = idx($metadata, PhabricatorFile::METADATA_IMAGE_HEIGHT);

      $history_uri = '/pholio/image/history/'.$image->getID().'/';
      $images[] = array(
        'id' => $image->getID(),
        'fullURI' => $file->getBestURI(),
        'pageURI' => $this->getImagePageURI($image, $mock),
        'historyURI' => $history_uri,
        'width' => $x,
        'height' => $y,
        'title' => $image->getName(),
        'desc' => $image->getDescription(),
        'isObsolete' => (bool)$image->getIsObsolete(),
      );
    }

    $navsequence = array();
    foreach ($mock->getImages() as $image) {
      $navsequence[] = $image->getID();
    }

    $login_uri = id(new PhutilURI('/login/'))
      ->setQueryParam('next', (string) $this->getRequestURI());
    $config = array(
      'mockID' => $mock->getID(),
      'panelID' => $panel_id,
      'viewportID' => $viewport_id,
      'commentFormID' => $this->getCommentFormID(),
      'images' => $images,
      'selectedID' => $selected_id,
      'loggedIn' => $this->getUser()->isLoggedIn(),
      'logInLink' => (string) $login_uri,
      'navsequence' => $navsequence,
    );
    Javelin::initBehavior('pholio-mock-view', $config);

    $mockview = '';

    $mock_wrapper = javelin_tag(
      'div',
      array(
        'id' => $viewport_id,
        'sigil' => 'mock-viewport',
        'class' => 'pholio-mock-image-viewport'
      ),
      '');

    $image_header = javelin_tag(
      'div',
      array(
        'id' => 'mock-image-header',
        'class' => 'pholio-mock-image-header',
      ),
      '');

    $mock_wrapper = javelin_tag(
      'div',
      array(
        'id' => $panel_id,
        'sigil' => 'mock-panel touchable',
        'class' => 'pholio-mock-image-panel',
      ),
      array(
        $image_header,
        $mock_wrapper,
      ));

    $inline_comments_holder = javelin_tag(
      'div',
      array(
        'id' => 'mock-inline-comments',
        'sigil' => 'mock-inline-comments',
        'class' => 'pholio-mock-inline-comments'
      ),
      '');

    $mockview[] = phutil_tag(
      'div',
        array(
          'class' => 'pholio-mock-image-container',
          'id' => 'pholio-mock-image-container'
        ),
      array($mock_wrapper, $inline_comments_holder));

    return $mockview;
  }

  private function getImagePageURI(PholioImage $image, PholioMock $mock) {
    $uri = '/M'.$mock->getID().'/'.$image->getID().'/';
    return $uri;
  }
}
