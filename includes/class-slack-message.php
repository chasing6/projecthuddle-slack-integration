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
    $this->client = new \Maknz\Slack\Client( $this->get_option( 'webhook' ) );
  }

  public function get_option( $setting ) {
    return get_option( 'ph_slack_' . $setting );
  }

	public function test_rest_message( $attr, $value, $object ) {
		$this->client = new \Maknz\Slack\Client( 'https://hooks.slack.com/services/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' );
		$this->client->to('#app-test')->send($attr);
	}

	public function cud_message( $id, $object ) {

		// bail if this is only a thread resolve
		// this fixes a bug where thread resolve/unresolve also sends a message
		if ( $object->comment_content == 'Approved' || $object->comment_content == 'Unapproved' ) {
			return;
		}

		$status = ! ph_mockup_is_approved( $object->comment_post_ID ) ? 'Approved' : 'Unapproved';

		// check if we should send
		if ( ! $this->get_option( 'send_unapprovals' ) && $status == 'Unapproved' ) {
			return;
		}

		$project = $this->get_parent_project( $id );
		$title = html_entity_decode( get_the_title( $project ) );

		$attach = array(
			'fallback' => $title . ' has been *' . $status . '*',
			'pretext' => 'Approval Status Change for *' . $title . '*',
			'title' => $title . ' has been ' . $status,
			'title_link' => get_the_permalink( $project ),
			'color' => $status == 'Approved' ? 'good' : 'danger',
			'fields' => array(
				array(
					'title' => $status . ' By',
					'value' => $object->comment_author_email,
					'short' => true,
				),
				array(
					'title' => $status . ' On',
					'value' => get_comment_date( 'm-d-Y g:iA', $object ),
					'short' => true,
				),
			),
		);

		foreach ( $this->get_channels() as $channel ) {
			$this->client->createMessage()->to( $channel )->enableMarkdown()->attach( $attach )->send();
		}
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
}
