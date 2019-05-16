<?php

class PH_Slack_Approval_Message{

  private $client;
  private $webhook = NULL;

  public function __construct() {
    $this->init();
  }

  private function init() {
    $this->webhook = get_option( 'ph_slack_webhook' );
    $this->client = new Maknz\Slack\Client( $this->webhook );
  }

  public function on_approval( $attr, $object ){

    $attach = array();

    $project = $this->get_parent_project( $attr );
    $title = html_entity_decode( get_the_title( $project ) );
    $status = ph_get_mockup_approval_status( $project );

    if ( isset( $status['by'] ) && isset( $status['on'] ) ) {
      $by = sanitize_text_field( $status['by'] );
      $on = sanitize_text_field( $status['on'] );
    }

    $approval = ph_mockup_is_approved( $project );

    // Bail if the project isn't approved
    if ( $approval  ){
      return;
    }

    // Build the attachement
    $attach = array(
      'fallback'      => 'New Image Approval for ' . $title,
      'pretext'       => "Approval for " . $title,
      'title'         => $title,
      'title_link'    => get_the_permalink($project),
      'color'         => 'good',
      'fields'        => array(
        array(
          'title'     => 'Approved By',
          'value'     => $by,
          'short'     => true
        ),
        array(
          'title'     => 'Approved On',
          'value'     => $object->comment_date,
          'short'     => true
        ),
      ),
    );

    // Where do we send it?
    $channel = get_option('ph_slack_approval_channels');

    // TODO: Send a message to multiple channels
    $this->send_message( $attach, $channel );
  }

  public function send_message( $attach, $channel ){
    $message = $this->client->createMessage()->to( $channel )->attach( $attach )->send();
  }

  public function approval_change( $attr, $value, $object ) {
    $project = $this->get_parent_project( $attr );

    $status = ph_get_mockup_approval_status( $project );

    $attach = array(
      'title' => 'Comment',
      'fields' => array(
        array(
          'title'     => '$status[total]',
          'value'     => $status['total'],
          'short'     => true
        ),
        array(
          'title'     => '$status[approved]',
          'value'     => $status['approved'],
          'short'     => true
        ),
        array(
          'title'     => '$object->comment_author',
          'value'     => $object->comment_author,
          'short'     => true
        ),
      ),
    );
    $this->send_message( $attach, '#app-test' );
  }

  public function basic_message( $attr, $value, $object ) {
    $this->client->send( 'BASIC MESSAGE' );
  }

  /**
   * Get the parent project. Abstraction of ph_get_parents_ids.
   */
  private function get_parent_project( $attr, $type = 'comment' ) {
    $parents = ph_get_parents_ids( $attr, $type  );
    return $parents['project'];
  }

  private function check_status( $comment ) {

  }
}

 ?>
