<?php
namespace PH_Slack;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Init {
  public function __construct(){
    $this->init();
  }

  public function init(){
    $this->define_constants();
    $this->load_dependancies();
    $this->load_hooks();
  }

  private function define_constants() {
    define ( 'PHS_DIR_PATH', trailingslashit( plugin_dir_path(__DIR__) ) );
    define ( 'PHS_INC_PATH', trailingslashit( PHS_DIR_PATH . 'inc' ) );
  }

  private function load_dependancies(){
    require_once PHS_DIR_PATH . 'vendor/autoload.php';
  }

  private function load_hooks(){

		$slack_msg = new Slack_Message();
		add_action( 'ph_rest_project_approval', [ $slack_msg, 'new_message' ], 1000, 3 );

    $settings = new Settings();
    add_filter( 'project_huddle_settings_fields', [ $settings, 'add_slack_settings' ] );

  }
}
