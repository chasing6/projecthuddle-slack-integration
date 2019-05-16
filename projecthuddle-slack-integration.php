<?php
/**
 * @link              http://c6creative.com
 * @since             1.0.0
 * @package           Innovate_Client
 *
 * @wordpress-plugin
 * Plugin Name:       ProjectHuddle Slack Integration
 * Plugin URI:        http://innovatedentalmarketing.com/
 * Description:       Bring ProjectHuddle to Slack
 * Version:           1.0.0
 * Author:            Scott McCoy
 * Author URI:        http://c6creative.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       innovate-client
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/chasing6/projecthuddle-slack-integration
 */

class PH_Slack{


  public function __construct(){
    $this->init();
    //echo get_option( 'ph_slack_webhook' );
  }

  public function init(){
    $this->load_dependancies();
    $this->load_hooks();
  }

  private function load_dependancies(){
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
    require_once plugin_dir_path(__FILE__) . 'inc/class-admin-page.php';
    require_once plugin_dir_path(__FILE__) . 'inc/class-approval-message.php';
    require_once plugin_dir_path(__FILE__) . 'inc/class-settings.php';
  }

  private function load_hooks(){

    $approval_msg = new PH_Slack_Approval_Message();
    add_action('ph_mockup_publish_approval', [ $approval_msg, 'on_approval' ], 10, 2 );
    //add_action( 'ph_mockup_rest_update_approval_attribute', [ $approval_msg, 'basic_message' ], 10, 3 );

    $settings = new PH_Slack_Settings();
    add_filter( 'project_huddle_settings_fields', [ $settings, 'add_slack_settings' ] );

  }
}

new PH_Slack;
