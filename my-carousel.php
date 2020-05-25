<?php
/*
Plugin Name: My Carousel
Plugin URI: http://varunv.sgedu.site/wordpress
Description: Whenever the mouse is moved  to the exit for the first time, the user customized popup appears and prompts the visitor to 
perform the function that the owner of the website wants. The owner can add his own code and add 
his own functionalities to the popup.
Author: Varun
Author URI: http://varunv.sgedu.site/wordpress
Version: 1.0
License: GPLv2
*/
// Initialise - load in translations
function my_loadtranslations () {
    $plugin_dir = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain( 'my-carousel', false, $plugin_dir );
}
add_action('plugins_loaded', 'my_loadtranslations');


// Load in the pages doing everything else!
require_once('src/my-init.php');
require_once('src/my-admin.php');
require_once('src/my-settings.php');
require_once('src/my-frontend.php');