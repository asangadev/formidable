/**
 * Add filter to validate field entry
 *
 * @param array   $errors        Array of validation errors.
 * @param object  $field         Field object being validated.
 * @param mixed   $posted_value  Value posted for the field.
 *
 * @return array  $errors        Updated array of validation errors.
 */
add_filter('frm_validate_field_entry', 'require_minimum_checkbox_number', 10, 3);

function require_minimum_checkbox_number($errors, $field, $posted_value){

    // Check if the field ID matches the file upload field ID
    if ($field->id == 12) { //change 12 to your file upload field id
        $total_file_size = 0;

        // Check if $posted_value is an array
        if (is_array($posted_value)) {
            // Loop through each file ID
            foreach ($posted_value as $file_id) {
                // Get the file path based on the file ID
                $file_path = get_attached_file($file_id);
                // Check if the file exists
                if ($file_path && file_exists($file_path)) {
                    // Get the file size and add it to the total
                    $total_file_size += filesize($file_path);
                }
            }
        }

        // Define the maximum allowed file size in bytes
        $max_file_size = 5 * 1024 * 1024; // 5MB

        // Check if total file size is greater than the maximum allowed size
        if ($total_file_size > $max_file_size) {
            // Add an error message to $errors array
            $errors['field' . $field->id] = 'Sorry, total file size cannot exceed 5MB. Please email your files to documents@test.com.au';
        }
    }

    // Return the updated array of validation errors
    return $errors;
}