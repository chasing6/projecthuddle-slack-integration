<?php
namespace PH_Slack;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class Settings {
  public function add_slack_settings( $settings ) {
    $settings['slack'] = array(
      'title' => 'Slack',
      'fields' => array(
        'slack_webhook' => array(
  				'id'          => 'slack_webhook',
  				'label'       => __( 'Webhook URL', 'project-huddle' ),
  				'description' => __( 'Webhooks can be found at <a href="https://my.slack.com/apps/manage/custom-integrations" target="blank">Incoming Webhooks</a>', 'project-huddle' ),
  				'type'        => 'text',
          'default'     => get_option( 'ph_slack_webhook' ),
  			),/*
        'approval_channels' => array(
  				'id'          => 'slack_approval_channels',
  				'label'       => __( 'Approval Message Channel(s)', 'project-huddle' ),
  				'description' => __( 'Where should we send project approval messages? Must include # or @', 'project-huddle' ),
  				'type'        => 'text',
          'default'     => get_option( 'ph_slack_approval_channels')
  			),
        'send_unapprovals' => array(
  				'id'          => 'slack_send_unapprovals',
  				'label'       => __( 'Send Unapprovals?', 'project-huddle' ),
  				'description' => __( 'Should we send unapproval messages to Slack?', 'project-huddle' ),
  				'type'        => 'checkbox',
  				'default'     => '',
  			)*/
      ),
    );

    return $settings;
  }
}
