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
  }

  public function init(){
    $this->load_dependancies();
    $this->load_hooks();


  }

  private function load_titan(){
    require_once plugin_dir_path(__FILE__) . 'vendor/titan/titan-framework-checker.php';
    add_filter( 'titan_checker_installation_notice', function(){return 'PH Slack requires Titan Framework to be installed.';});
    add_filter( 'titan_checker_activation_notice', function(){return 'PH Slack requires Titan Framework to be activated.';});
  }

  private function load_dependancies(){
    $this->load_titan();
    require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
    require_once plugin_dir_path(__FILE__) . 'inc/class-admin-page.php';
    require_once plugin_dir_path(__FILE__) . 'inc/class-approval-message.php';
  }

  private function load_hooks(){
    // Create an admin page under the ProjectHuddle menu item
    add_action( 'tf_create_options', ['PH_Slack_Admin_Page', 'add_admin_page']);

    $approval_msg = new PH_Slack_Approval_Message();
    add_action('ph_mockup_publish_approval', [$approval_msg, 'on_approval'], 10, 2 );
  }
}

new PH_Slack;
