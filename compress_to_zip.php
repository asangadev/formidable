/**
 * Add filter to adjust field before creating entry
 *
 * @param array  $values  Array of field values.
 *
 * @return array  $values  Updated array of field values.
 */
add_filter('frm_pre_create_entry', 'adjust_my_field');

function adjust_my_field($values){

    // Check if the form ID matches the specified form ID
    if ( $values['form_id'] == 2 ) { //change 2 to your form id
        
        // Check if item_meta[12] exists and it's an array
        if (isset($values['item_meta'][12]) && is_array($values['item_meta'][12])) {
            // Initialize ZipArchive in memory
            $zip = new ZipArchive();

            // Create a temporary file to store the ZIP archive
            $temp_file = tempnam(sys_get_temp_dir(), 'zip');
            
            // Open the temporary file for writing
            if ($zip->open($temp_file, ZipArchive::CREATE) === TRUE) {
                // Loop through each file ID
                foreach ($values['item_meta'][12] as $file_id) {
                    // Get the file path based on the file ID
                    $file_path = get_attached_file($file_id);
                    // Check if the file exists
                    if ($file_path && file_exists($file_path)) {
                        // Add the file to the ZIP archive
                        $zip->addFile($file_path, basename($file_path));
                    }
                }
                // Close the ZIP file
                $zip->close();

                // Read the contents of the temporary file and encode it to base64
                $base64_zip_content = base64_encode(file_get_contents($temp_file));

                // Remove the temporary file
                unlink($temp_file);

                // Assign the base64 encoded content to a specific field
                $values['item_meta'][14] = $base64_zip_content;
            }
        }
    }

    // Return the updated array of field values
    return $values;
}