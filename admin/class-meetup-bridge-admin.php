<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://diadus.net
 * @since      1.0.0
 *
 * @package    Meetup_Bridge
 * @subpackage Meetup_Bridge/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Meetup_Bridge
 * @subpackage Meetup_Bridge/admin
 * @author     Boris Diadus <boris@diadus.net>
 */
class Meetup_Bridge_Admin {

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

    /**
     * @param int $post_ID
     * @param WP_Post $post
     *
     * @since 1.0.0
     */
    public function save_post($post_ID, $post)
    {
        $options = get_option('meetup-bridge-fields', array());
        if (!$options) {
            return;
        }
        if ($post->post_type != $options['post-type']) {
            return;
        }

        $builder = new Meetup_Bridge_Builder();

        $meetup_data = $builder->build_meetup_data_according_to($post_ID);
        $this->update_meetup_event($post_ID, $meetup_data);
	}

    /**
     * @param int $post_ID
     * @param array $data
     * @throws Exception
     *
     * @since 1.0.0
     */
    protected function update_meetup_event($post_ID, $data)
    {
        require_once dirname(__FILE__) . '/../includes/class-meetup-api.php';
        try {
            if ($this->is_event_created_for_post($post_ID)) {
                $api = new Meetup(get_option('meetup-bridge-meetup-api'), get_option('meetup-bridge-meetup-group-slug'));
                $response = $api->updateEvent($data, $this->get_event_id_for_post($post_ID));
            } else {
                $this->create_event_and_attach_to_post($post_ID, $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage(); die;
        }

        $this->update_meetup_event_photos($post_ID);
    }

    protected function update_meetup_event_photos($post_id)
    {
        $attachments = get_attached_media('image', $post_id);
        foreach ($attachments as $attachment) {
            $this->upload_photo_to_meetup_event($attachment, $this->get_event_id_for_post($post_id));
        }
    }

    /**
     * @param WP_Post $attachment
     * @param int $event_id
     */
    protected function upload_photo_to_meetup_event($attachment, $event_id)
    {
        if (get_post_meta($attachment->ID, 'meetup_photo_id', true)) {
            return;
        }

        $file = get_attached_file($attachment->ID);
        require_once dirname(__FILE__) . '/../includes/class-meetup-api.php';

        $api = new Meetup(get_option('meetup-bridge-meetup-api'), get_option('meetup-bridge-meetup-group-slug'));
        $data['caption'] = $attachment->post_title;
        $data['photo'] = new CURLFile($file, $attachment->post_mime_type, $attachment->post_title);
        try {
            $response = $api->postPhoto($data, $event_id);
        } catch (Exception $e) {
            $a = 1;
        }

        update_post_meta($attachment->ID, 'meetup_photo_id', $response->id);
    }

    /**
     * @param int $post_id
     * @param array $data
     * @throws Exception
     */
    protected function create_event_and_attach_to_post($post_id, $data)
    {
        $api = new Meetup(get_option('meetup-bridge-meetup-api'), get_option('meetup-bridge-meetup-group-slug'));
        $response = $api->postEvent($data);
        $this->attach_event_id_to_post($post_id, $response->id);
    }

    /**
     * @param int $post_id
     */
    protected function is_event_created_for_post($post_id)
    {
        return $this->get_event_id_for_post($post_id) != false;
    }

    /**
     * @param int $post_id
     */
    protected function get_event_id_for_post($post_id)
    {
        return get_post_meta($post_id, 'meetup_event_id', true);
    }

    /**
     * @param int $post_id
     * @param int $event_id
     */
    protected function attach_event_id_to_post($post_id, $event_id)
    {
        update_post_meta($post_id, 'meetup_event_id', $event_id);
    }



}
