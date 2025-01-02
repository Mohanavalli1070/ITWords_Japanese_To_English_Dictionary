<?php

// Open the english3.txt file for reading
$txt_file = fopen("C:/Users/Mohanavalli1/htdocs/translation_app_new/english3.txt", 'r');  // Corrected file path

// Check if the file is opened successfully
if ($txt_file) {
    // Open the output CSV file for writing in the same directory as the PHP script
    $csv_file = fopen('C:/Users/Mohanavalli1/htdocs/translation_app_new/english3.csv', 'w');  // Absolute path for output CSV file

    // Check if the CSV file was opened successfully
    if ($csv_file) {
        // Write the column header to the CSV
        fputcsv($csv_file, ['English Word']);

        // Read the file line by line and write each word to the CSV
        while (($line = fgets($txt_file)) !== false) {
            // Trim any extra whitespace or newline characters
            $word = trim($line);

            // Ensure only non-empty words are written
            if (!empty($word)) {
                // Write the word to the CSV
                fputcsv($csv_file, [$word]);
            }
        }

        // Close the files after processing
        fclose($txt_file);
        fclose($csv_file);

        echo "CSV conversion completed successfully!";
    } else {
        echo "Error opening the CSV file for writing.";
    }
} else {
    echo "Error opening the text file.";
}

?>
