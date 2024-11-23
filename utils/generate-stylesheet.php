<?php

function download_file_curl($url, $destination)
{
    $file = fopen($destination, "w+");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_FILE, $file);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($curl);
    curl_close($curl);
    fclose($file);
    return $data;
}


function get_domain_with_port($urlString)
{
    // Parse the URL string
    $parsed_url = parse_url($urlString);

    // Check if parsing was successful
    if (!$parsed_url) {
        return null;  // Return null for invalid URLs
    }

    // Extract host and port
    $host = $parsed_url['host'];
    $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';

    // Rebuild the URL with potential port
    $root_domain = $host . $port;

    return $root_domain;
}


function update_file_location_for_page($path, $post_id)
{
    update_post_meta(
        $post_id,
        'css_optimise_file',
        basename($path)
    );
}


function update_file_hash_for_page($hash, $post_id)
{
    update_post_meta(
        $post_id,
        'css_optimise_hash',
        $hash
    );
}


function optimise_CSS($URL, $post_id)
{
    $existing_file = get_post_meta($post_id, 'css_optimise_file', true);
    $existing_hash = get_post_meta($post_id, 'css_optimise_hash', true);


    $excluded_files = get_option('excluded_urls', "");

    //run the optimisation service, get file resonse, then download it and save it to the plugin directory
    $curl = curl_init();
    $endpoint_url = get_option('endpoint_url', "");

    if (!$endpoint_url) {
        return wp_send_json(["err" => "No endpoint URL set. Please set one in the plugin settings page and try again."]);
    }

    curl_setopt_array($curl, [
        CURLOPT_URL => $endpoint_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'url' => $URL,
            "excludedFiles" => $excluded_files,
            "api_token" => get_option('api_token', ""),
        ]),
        CURLOPT_HTTPHEADER => [
            "content-type: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);



    if ($err) {
        echo "cURL Error #:" . $err;
    }

    /*
    - response body: {css: "domain.com/filename.css", hash: sha256hash}
    - Using this, we need to download the file, and write it to the plugin folder DIR/optimised_css/ folder
    */


    $decoded = json_decode($response);
    $endpoint_url = get_option('endpoint_url', "");
    $root_domain = get_domain_with_port($endpoint_url);

    $fullURL = $root_domain . "/" . $decoded->css;
    $filename = $decoded->css;
    $hash = $decoded->hash;

    $optmised_css_full_path = plugin_dir_path(__FILE__) . "../optimised_css/" . $filename;

    /*

    - If the hash stays the same when we next attempt to optimise, we should not increase the usage count.
    - If the hash changes, we should increase the usage count for the applicable hash, if it's available.
    - We should also decrement the counter if the hash is different.
    - If the usage count is 1, and we're about to change from using it, then we can get rid of it, and its record as they are no longer needed.
    */

    if ($existing_hash !== $hash) {
        $result = get_record($existing_hash);
        if (!empty($result)) {
            if ($existing_hash !== "") {
                if ($result->usage_qty <= 1) {
                    unlink(plugin_dir_path(__FILE__) . "../optimised_css/" . $result->filename);
                    //delete record
                    delete_record($existing_hash);
                }
            }
            update_record($result->usage_qty - 1, $existing_hash); //decrement
        }
    }


    $hash_exists = does_hash_exist($hash); //check if the styles are already produced using an existing file
    if (!$hash_exists) {
        download_file_curl($fullURL, $optmised_css_full_path);
        insert_record(basename($filename), $hash);
        return basename($optmised_css_full_path);
    } else {
        if ($existing_hash !== $hash) {
            //update count of usage
            $result = get_record($hash);
            error_log("result: " . json_encode($result));
            if (!empty($result)) {
                update_record($result->usage_qty + 1, $result->hash);
                update_file_location_for_page($result->filename, $post_id);
                return $result->filename;
            }
        }
    }

    update_file_hash_for_page($hash, $post_id);
}



function css_optimise_generate_stylesheet_callback()
{
    $URL = $_POST['sbp_url'];
    $post_id = $_POST['post_ID'];

    /*
	- send http request to outside 3rd party optimisation service
	- Send URL to 3rd party service for optimisation
	- Response will be a URL to the optimised CSS file
	- Save the URL to the optimised CSS file in a relevant directory
	- The URL will expire after 5 mins
	- We must also remove any old or existing stylesheets when we generate a new one to avoid increasing disk size
	*/

    //remove existing file if there is one
    $result = optimise_CSS($URL, $post_id);
    // wp_send_json(json_encode($result));

    return wp_send_json($result);
}
