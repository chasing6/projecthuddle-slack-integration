<?php

class PH_Slack_Approval_Message{

  public function on_approval( $attr, $object ){

    $text = 'Generic Text';
    $attach = array();


    $parents = ph_get_parents_ids( $attr, 'comment'  );
    $project = $parents['project'];
    $title = get_the_title( $project );
    $status = ph_get_mockup_approval_status( $project );
    $permalink = get_the_permalink($project);

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
      $text = "Approval for $title";

      // Build the attachement
      $attach = array(
        'fallback'      => 'New Image Approval for ' . $title,
        'pretext'       => $text,
        'title'         => $title,
        'title_link'    => $permalink,
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
    $webhook = TitanFramework::getInstance('ph-slack')->getOption('webhook');
    $channel = TitanFramework::getInstance('ph-slack')->getOption('approval_channel');

    // Build client and explicit message
    $client = new Maknz\Slack\Client($webhook);

    // TODO: Send a message to multiple channels
    $message = $client->createMessage()->to($channel)->attach($attach)->send();
  }

  private function check_approval($project){
    /*$status = false;
    if ( $object->comment_content === 'Approved') {
      $status = true;
  	} */
    // just return true for testing
    return ph_mockup_is_approved( $project );
  }

  public function send_message($text = 'No Message', $channel = ''){

    //$text = 'Message Text';

    $webhook = TitanFramework::getInstance('ph-slack')->getOption('webhook');

    $client = new Maknz\Slack\Client($webhook);

    $message = $client->createMessage()->to($channel)->setText($text)->send();

    var_dump( $message );
  }
}

 ?>
