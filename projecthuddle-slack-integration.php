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
 * Version:           1.0.1
 * Author:            Scott McCoy
 * Author URI:        http://c6creative.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       innovate-client
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/chasing6/projecthuddle-slack-integration
 */

require_once plugin_dir_path( __FILE__ ) . 'libraries/autoloader.php';

new PH_Slack\Init;
