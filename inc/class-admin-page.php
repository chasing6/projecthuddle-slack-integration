<?php

class PH_Slack_Admin_Page{

  public function add_admin_page(){
    $titan = TitanFramework::getInstance('ph-slack');
    $panel = $titan->createAdminPanel( array(
      'name'      => 'Slack Webhook',
      // /'title'     => 'ProjectHuddle Slack Webhook',
      'id'        => 'ph-slack',
      'parent'    => 'edit.php?post_type=ph-project',
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
      'type'    => 'save'
    ));

  }
}


  ?>