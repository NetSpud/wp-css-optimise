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


function updateOptimisedFileLocation($path, $post_id)
{
    update_post_meta(
        $post_id,
        'css_optimise_file',
        basename($path)
    );
}


function optimise_CSS($URL, $post_id)
{
    $existing_file = get_post_meta($post_id, 'css_optimise_file', true);
    if ($existing_file !== "" || !empty($existing_file)) {
        error_log("deleting old CSS file of path:" . $existing_file);
        unlink($existing_file);
    }

    $excluded_files = get_option('excluded_urls', "");

    //run the optimisation service, get file resonse, then download it and save it to the plugin directory
    $curl = curl_init();
    $endpoint_url = get_option('endpoint_url');
    curl_setopt_array($curl, [
        CURLOPT_PORT => "3000",
        CURLOPT_URL => $endpoint_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'url' => $URL,
            "excludedFiles" => $excluded_files,
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
- response body: {css: "domain.com/filename.css"}
- Using this, we need to download the file, and write it to the plugin DIR/optimised_css/ folder
*/




    $decoded = json_decode($response);
    $api_URL = get_option("endpoint_url");
    $fullURL = $api_URL . $decoded->css;
    $filename = basename($fullURL);
    $optmised_css_full_path = plugin_dir_path(__FILE__) . "../optimised_css/" . $filename;

    //make GET request to endpoint to download file from $fullURL
    //write result of that to file at path from $optmised_css_full_path

    download_file_curl($fullURL, $optmised_css_full_path, $filename);
    updateOptimisedFileLocation($filename, $post_id);



    return basename($optmised_css_full_path);
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
