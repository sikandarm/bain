<?php
/*
Plugin Name: Cakes List
Plugin URI: abc.com
Description: For listing of cakes.
Version: 1
Author: Sikandar Maqbool

*/
define('CAKE_Path', plugin_dir_path(__FILE__));
include_once(CAKE_Path . 'frontend.php');
register_activation_hook( __FILE__, 'cakesTable');


function cakesTable() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cakes_list';
    $sql = "CREATE TABLE `$table_name` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(220) DEFAULT NULL,
  `recipe` varchar(220) DEFAULT NULL,
    `attachment_id` varchar(220) DEFAULT NULL,
  PRIMARY KEY(id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  ";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

add_action('admin_menu', 'addAdminPageContent');

add_shortcode('cakes_list', 'render_cakes');

function render_cakes()
{
    ob_start();
    frontend_listing();
    return ob_get_clean();
}


function addAdminPageContent()
{
    add_menu_page('Cakes', 'Cakes', 'manage_options', __FILE__, 'cakesAdminPage', 'dashicons-wordpress');
}

function cakesAdminPage()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cakes_list';
    if (isset($_POST['newsubmit'])) {
        $name = $_POST['newname'];
        $recipe = $_POST['newrecipe'];
        $attachment_id = $_POST['new_attachment_id'];
        $wpdb->query("INSERT INTO $table_name(name,recipe,attachment_id) VALUES('$name','$recipe','$attachment_id')");
        echo "<script>location.replace('admin.php?page=cake/index.php');</script>";
    }
    if (isset($_POST['uptsubmit'])) {
        $id = $_POST['uptid'];
        $name = $_POST['uptname'];
        $recipe = $_POST['uptrecipe'];
        $attachment_id = $_POST['upt_attachment'];
        $wpdb->query("UPDATE $table_name SET name='$name',recipe='$recipe',attachment_id=$attachment_id WHERE id='$id'");
        echo "<script>location.replace('admin.php?page=cake/index.php');</script>";
    }
    if (isset($_GET['del'])) {
        $del_id = $_GET['del'];
        $wpdb->query("DELETE FROM $table_name WHERE id='$del_id'");
        echo "<script>location.replace('admin.php?page=cake/index.php');</script>";
    }
    ?>
    <div class="wrap">
    <?php wp_enqueue_media(); ?>
    <h2>Cakes List</h2>
    <?php if (!isset($_GET['upt'])) { ?>
        <table class="wp-list-table widefat striped">
            <thead>
            <tr>
                <th width="25%"> ID</th>
                <th width="25%">Name</th>
                <th width="25%">Recipe</th>
                <th> Image</th>
                <th width="25%">Actions</th>
            </tr>
            </thead>
            <tbody>
            <form action="" method="post">
                <tr>
                    <td></td>
                    <td><input type="text" id="newname" name="newname"></td>
                    <td><input type="text" id="newrecipe" name="newrecipe"></td>
                    <input type='hidden' id='attachment_new' name='new_attachment_id' value=''/>
                    <td><input type="button" class="button upload_image_button" onclick="uploadImageButton('new')"
                               value="<?php _e('Upload image'); ?>"/></td>
                    <td>
                        <button id="newsubmit" name="newsubmit" type="submit">INSERT</button>
                    </td>
                </tr>
            </form>
            <?php
            $result = $wpdb->get_results("SELECT * FROM $table_name");
            foreach ($result as $print) {
                echo "
              <tr>
                <td width='25%'>$print->id</td>
                <td width='25%'>$print->name</td>
                <td width='25%'>$print->recipe</td>
              "; ?>
                <td> <?php wp_enqueue_media(); ?>
                    <img src='<?php echo wp_get_attachment_url($print->attachment_id); ?>' width='200'>
                </td>
                <?php
                echo "
                <td width='25%'><a href='admin.php?page=cake/index.php&upt=$print->id'><button type='button'>UPDATE</button></a> <a href='admin.php?page=cake/index.php&del=$print->id'><button type='button'>DELETE</button></a></td>
              </tr>";
            }
            ?>
            </tbody>
        </table>
    <?php } ?>
    <br>
    <br>
    <?php
    if (isset($_GET['upt'])) {
        $upt_id = $_GET['upt'];
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id'");
        foreach ($result as $print) {
            $name = $print->name;
            $recipe = $print->recipe;
        }
        echo "
        <table class='wp-list-table widefat striped'>
          <thead>
            <tr>
             
              <th width='25%'>Name</th>
              <th width='25%'>Recipe</th>
              <th></th>
               <th></th>
               <th></th>
              <th width='25%'>Actions</th>
            </tr>
          </thead>
          <tbody>
            <form action='' method='post'>
              <tr>
                <input type='hidden' id='uptid' name='uptid' value='$print->id'>
                <td width='25%'><input type='text' id='uptname' name='uptname' value='$print->name'></td>
                <td width='25%'><input type='text'  name='uptrecipe' value='$print->recipe'></td>
                <input type='hidden' id='attachment_$print->id' name='upt_attachment' value='$print->attachment_id'>
                ";
        ?>
        <td><input data='<?php echo $print->id; ?>' type='button' onclick='uploadImageButton(<?php echo $print->id; ?>)'
                   class='button upload_image_button' value='<?php _e('Upload image'); ?>'/>
        <td/>
        <td>
            <?php wp_enqueue_media(); ?>
            <img id='image-preview' src='<?php echo wp_get_attachment_url($print->attachment_id); ?>' width='200'>
        </td>
        <td width='25%'>
            <button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button>
            <a href='admin.php?page=crud/crud.php'>
                <button type='button'>CANCEL</button>
            </a></td>
        </tr>
        </form>
        </tbody>
        </table>

        <?php
    }


    $my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0); ?>
    <script type='text/javascript'>
        jQuery(document).ready(function ($) {
            var file_frame;
            var wp_media_post_id =  0; //wp.media.model.settings.post.id; // Store the old id
            var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
            // jQuery('.upload_image_button').on('click', function( event ){

            // Restore the main ID when the add media button is pressed
            jQuery('a.add_media').on('click', function () {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });

        function uploadImageButton(id) {
            var file_frame;
            var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
            var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if (file_frame) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param('post_id', set_to_post_id);
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }
            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false // Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                // Do something with attachment.id and/or attachment.url here
                jQuery('#image-preview').attr('src', attachment.url).css('width', '200');
                jQuery('#attachment_' + id).val(attachment.id);
                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });
            // Finally, open the modal
            file_frame.open();
        };
    </script>
    <?php
}
