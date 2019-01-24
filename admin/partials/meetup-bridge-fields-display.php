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

/** @var array $post_types */
$post_types = get_post_types();

/** @var wpdb $wpdb */
global $wpdb;
$meta_keys = $wpdb->get_col("SELECT DISTINCT meta_key FROM {$wpdb->postmeta}");

$fields = array(
    '' => '-',
    'post_title' => 'Post Title',
    'post_content' => 'Post Content',
    'post_date' => 'Post Date',
    'post_date_gmt' => 'Post Date GMT',
    'post_excerpt' => 'Post Excerpt'
);
foreach ($meta_keys as $meta_key) {
    $fields['meta-' . $meta_key] = 'Meta: ' . $meta_key;
}

$meetup_fields = array(
    'description',
    'duration',
    'lat',
    'lon',
    'name',
    'time',
    'how_to_find_us'
);

function render_select($field_name, $options)
{
    $current_values = get_option('meetup-bridge-fields', array());

    $options_html = '';
    foreach ($options as $option_name => $option_title) {
        if (is_numeric($option_name)) {
            $option_name = $option_title;
        }

        $selected = $option_name == $current_values[$field_name] ? 'selected' : '';
        $options_html .= "<option value=\"$option_name\" $selected>$option_title</option>";
    }

    echo "<select name=\"fields[$field_name]\">$options_html</select>";
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Meetup-Bridge Fields</h1>
    <form method="post">
        <table>
            <thead></thead>
            <tbody>
            <tr>
                <td><label for="post-type">Post Type:</label></td>
                <td>
                    <?php render_select('post-type', $post_types); ?>
                </td>
            </tr>
            <tr><td colspan="2"><hr/></td></tr>
            <?php foreach ($meetup_fields as $meetup_field) : ?>
                <tr>
                    <td><label for="<?php echo $meetup_field; ?>"><?php echo $meetup_field; ?></label></td>
                    <td>
                        <?php render_select($meetup_field, $fields); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <br/>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">

        <input type="hidden" name="fields[nonce]" value="<?php echo wp_create_nonce('meetup-bridge-fields'); ?>"/>
    </form>
</div>
