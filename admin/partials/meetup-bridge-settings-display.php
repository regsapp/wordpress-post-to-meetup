<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://diadus.net
 * @since      1.0.0
 *
 * @package    Meetup_Bridge
 * @subpackage Meetup_Bridge/admin/partials
 */

$meetup_api = get_option('meetup-bridge-meetup-api');
$meetup_group_slug = get_option('meetup-bridge-meetup-group-slug');
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Meetup-Bridge Settings</h1>
    <form method="post">
        <input type="text" name="meetup-api" value="<?php echo $meetup_api; ?>"/>
        <input type="text" name="meetup-group-slug" value="<?php echo $meetup_group_slug; ?>"/>
        <br/><br/>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">

        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('meetup-bridge-settings'); ?>"/>
        <input type="hidden" name="settings" value="1"/>
    </form>
</div>
