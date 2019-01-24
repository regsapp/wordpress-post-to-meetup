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
        <table class="form-table">

            <tbody><tr>
                <th scope="row"><label for="meetup-api">Meetup API Key</label></th>
                <td><input class="regular-text" type="text" name="meetup-api" value="<?php echo $meetup_api; ?>"/>
                    <p class="description" id="tagline-description">You can get it there -> <a href="https://secure.meetup.com/meetup_api/key/">https://secure.meetup.com/meetup_api/key/</a></p></td>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="meetup-group-slug">Meetup Group URL (slug)</label></th>
                <td><input class="regular-text" type="text" name="meetup-group-slug" value="<?php echo $meetup_group_slug; ?>"/>
                    <p class="description" id="tagline-description">For example, highlighted part of <span style="color: hsla(0, 0%, 76%, 1);">https://www.meetup.com/<span style="color: #000;">Web-Summit-SaaS-Mojitos</span>/events/</span></p></td>
            </tr>
            </tbody></table>
        <br/><br/>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">

        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('meetup-bridge-settings'); ?>"/>
        <input type="hidden" name="settings" value="1"/>
    </form>
</div>
