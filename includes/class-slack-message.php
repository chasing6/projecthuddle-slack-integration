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
		$settings = array(
			'allow_markdown' => true,
		);
    $this->webhook = $webhook;
    $this->client = new \Maknz\Slack\Client( $this->get_option( 'webhook' ), $settings );
  }

	private function build_client( $webhook = null ) {
		$settings = array(
			'allow_markdown' => true,
		);
    $this->client = new \Maknz\Slack\Client( $this->get_option( 'webhook' ), $settings );
		return $this->client;
	}

  public function get_option( $setting ) {
    return get_option( 'ph_slack_' . $setting );
  }

	private function get_channels() {
		$channels = $this->get_option( 'approval_channels' );
		$array = array_map('trim', explode(',', $channels ) );
		return array_map('trim', explode(',', $channels ) );
	}

	private function get_parent_project( $attr, $type = 'comment' ) {
    $parents = ph_get_parents_ids( $attr, $type  );
    return $parents['project'];
  }

	public function new_message( $approval_term, $project, $value ) {

		// bail if the whole project isn't approved
		if ( ! ph_mockup_is_approved( $project ) ) {
			return;
		}

		$title = html_entity_decode( get_the_title( $project ) );
		$status = 'Approved';
		$approval_status = ph_get_mockup_approval_status( $project );
		$user = wp_get_current_user();

		$attach = array(
			'fallback' => $title . ' has been *' . $status . '*',
			'pretext' => 'Approval Status Change for *' . $title . '*',
			'title' => $title . ' has been ' . $status,
			'title_link' => get_the_permalink( $project ),
			'color' => $status == 'Approved' ? 'good' : 'danger',
			'fields' => array(
				array(
					'title' => $status . ' By',
					'value' => $user->user_email,
					'short' => true,
				),
				array(
					'title' => $status . ' On',
					'value' => current_time( 'm-d-Y g:iA' ),
					'short' => true,
				),
			),
		);

		$this->client->createMessage()->enableMarkdown()->attach( $attach )->send();

	}
}
