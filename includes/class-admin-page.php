<?php
namespace PH_Slack;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class Admin_Page{

  public function add_admin_page(){
    $titan = TitanFramework::getInstance('ph-slack');
    $panel = $titan->createAdminPanel( array(
      'name'      => 'Slack Webhook',
      'id'        => 'ph-slack',
      'parent'    => 'project-huddle',
      'position'  => 1000,
    ));
    $panel->createOption( array(
      'name'    => 'Webhook URL',
      'id'      => 'webhook',
      'type'    => 'text',
      'desc'    => 'Webhooks can be found at <a href="https://my.slack.com/apps/manage/custom-integrations" target="blank">Incoming Webhooks</a>'
    ));
    $panel->createOption( array(
      'name'    => 'Approval Message Channel',
      'id'      => 'approval_channel',
      'type'    => 'text',
      'desc'    => 'Where should we send project approval messages? Must include # or @',
      'placeholder' => '#general'
    ));
    $panel->createOption( array(
      'name'    => 'Send Unapprovals',
      'id'      => 'send_unapprovals',
      'type'    => 'checkbox',
      'desc'    => 'Should we also send unapprovals to Slack?',
    ));

    $panel->createOption( array(
      'type'    => 'save'
    ));

  }
}


  ?>
