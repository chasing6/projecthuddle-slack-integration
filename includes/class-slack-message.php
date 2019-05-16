<?php
namespace PH_Slack;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Slack_Message {

  private $client;
  private $webhook = NULL;
  private $channels = array();
  private $status;

  public function __construct( $webhook = null ) {
    if ( ! is_null( $webhook ) ) {
      $webhook = $this->get_option( 'webhook' );
    }
    $this->webhook = $webhook;
    $this->build_client();
  }

  public function set_webhook( $webhook ) {
    $this->webhook = $webhook;
    return $this;
  }

  public function build_client( $webhook = null ) {
    if ( is_null( $webhook ) ) {
      $webhook = $this->webhook;
    }
    $this->client = new \Maknz\Slack\Client( $webhook );
    return $this;
  }

  public function get_option( $setting ) {
    return get_option( 'ph_slack_' . $setting );
  }
}
