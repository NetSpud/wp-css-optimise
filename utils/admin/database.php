<?php

function add_db_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'css_optimise';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
	`hash` varchar(64) DEFAULT NULL,
	`filename` VARCHAR(40) DEFAULT NULL,
	`usage_qty` INT(1) DEFAULT '1',
	PRIMARY KEY (`hash`)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'insert_record');

function insert_record($filename, $hash)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'css_optimise';

    $result = $wpdb->insert(
        $table_name,
        array(
            'filename' => $filename,
            'hash' => "$hash",
        ),
        array('%s', '%s')
    );

    if ($result === false) {
        error_log("Error inserting record: " . $wpdb->last_error);
    } else {
        error_log("Record inserted successfully, ID: " . $wpdb->insert_id);
    }
}

function get_record($hash)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'css_optimise';
    $result = $wpdb->prepare("SELECT * FROM $table_name WHERE hash = %s", $hash);
    return $wpdb->get_row($result);
}

function update_record($count, $hash)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'css_optimise';

    $query = $wpdb->prepare("UPDATE $table_name SET usage_qty = %d WHERE hash = %s", $count, $hash);
    $result = $wpdb->query($query);

    return $result;
}

function delete_record($hash)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'css_optimise';
    $query = $wpdb->prepare(
        "DELETE FROM $table_name WHERE hash = %s",
        $hash
    );
    $result = $wpdb->query($query);
    return $result;
}

function does_hash_exist($hash)
{
    /*
    - We are comparing the hash of the file, to hashes already saved.
    - If the provided hash already exists in the database, we can use the existing file
    - If the hash does not exist, we need to retrieve the file generated by the optimisation service 
    */
    $result = get_record($hash);
    return !empty($result);
}
