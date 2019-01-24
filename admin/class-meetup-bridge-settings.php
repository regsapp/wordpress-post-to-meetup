<?php
/**
 * @link       http://diadus.net
 * @since      1.0.0
 *
 * @package    Meetup_Bridge
 * @subpackage Meetup_Bridge/admin
 */

/**
 * @package    Meetup_Bridge
 * @subpackage Meetup_Bridge/admin
 * @author     Boris Diadus <boris@diadus.net>
 */
class Meetup_Bridge_Settings {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function admin_menu()
    {
        add_submenu_page('options-general.php','Meetup Bridge', 'Meetup Bridge', 'administrator', 'meetup_bridge_settings', array($this, 'process_settings_page'));
        add_submenu_page('options-general.php','Meetup Fields', 'Meetup Fields', 'administrator', 'meetup_bridge_fields', array($this, 'process_fields_page'));
    }

    public function process_settings_page()
    {
        if (isset($_POST['settings'])) {
            $this->try_to_save_settings($_POST);
        }

        include 'partials/meetup-bridge-settings-display.php';
    }

    public function process_fields_page()
    {
        if (isset($_POST['fields'])) {
            $this->try_to_save_fields($_POST['fields']);
        }

        include 'partials/meetup-bridge-fields-display.php';
    }

    protected function try_to_save_settings($fields)
    {
        if (!wp_verify_nonce($fields['nonce'], 'meetup-bridge-settings')) {
            die('Nonce error');
        }

        $names = array(
            'meetup-api',
            'meetup-group-slug'
        );

        foreach ($names as $name) {
            if (!isset($fields[$name]) || !$fields[$name]) {
                continue;
            }

            update_option('meetup-bridge-' . $name, $fields[$name]);
        }
    }

    protected function try_to_save_fields($fields)
    {
        if (!wp_verify_nonce($fields['nonce'], 'meetup-bridge-fields')) {
            die('Nonce error');
        }
        unset($fields['nonce']);

        update_option('meetup-bridge-fields', $fields);
    }

}