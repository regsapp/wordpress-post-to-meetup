<?php

/**
 * The meetup event builder functionality.
 *
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
class Meetup_Bridge_Builder {

    /**
     * @param $post_id
     *
     * @return array
     */
    public function build_meetup_data_according_to($post_id)
    {
        $options = get_option('meetup-bridge-fields', array());
        if (!$options) {
            return array();
        }
        $post = get_post($post_id);
        $event = array();

        foreach ($options as $event_field => $post_field) {
            if (!$post_field) {
                continue;
            }

            if (substr($post_field, 0, 4) == 'meta') {
                $value = get_post_meta($post_id, substr($post_field, 5), true);
            } else {
                $value = $post->$post_field;
            }

            $event[$event_field] = $value;
        }

        if ($event['time']) {
            $event['time'] = strtotime($event['time']) . '000';
        }

        if ($event['lat']) {
            $event['lat'] = number_format($event['lat'], 5, '.', '');
            $event['lon'] = number_format($event['lon'], 5, '.', '');
        }

        return $event;
    }

}