<?php

class PH_Slack_Approval_Message{

  private $client;

  public function on_approval( $attr, $object ){

    $attach = array();

    $parents = ph_get_parents_ids( $attr, 'comment'  );
    $project = $parents['project'];
    $title = html_entity_decode( get_the_title( $project ) );
    $status = ph_get_mockup_approval_status( $project );

    if ( isset( $status['by'] ) && isset( $status['on'] ) ) {
      $by = sanitize_text_field( $status['by'] );
      $on = sanitize_text_field( $status['on'] );
    }

    // Bail if the project isn't approved
    if ( !$this->check_approval($project)  ){
      return;
    }
    // Get ph approval
    $mockup_approved = ph_mockup_is_approved( $project );

    if ( $mockup_approved ){

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

    }

    // Where do we send it?
    $channel = TitanFramework::getInstance('ph-slack')->getOption('approval_channel');

    // Build client and explicit message
    $this->build_client();

    // TODO: Send a message to multiple channels
    $this->send_message($attach, $channel );
  }

  private function build_client(){
    $webhook = TitanFramework::getInstance('ph-slack')->getOption('webhook');
    // Build client and explicit message
    $this->client = new Maknz\Slack\Client($webhook);
  }

  private function check_approval($project){
    /*$status = false;
    if ( $object->comment_content === 'Approved') {
      $status = true;
  	} */
    // just return true for testing
    return ph_mockup_is_approved( $project );
  }

  public function send_message($attach, $channel){
    $message = $this->client->createMessage()->to($channel)->attach($attach)->send();

  }
}

 ?>
