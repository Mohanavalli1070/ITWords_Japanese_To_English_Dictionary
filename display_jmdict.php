<?php include 'includes/db.php'; ?>
<?php include 'includes/Header.php'; ?>

<?php
$filePath = 'JMdict.txt';

echo "<h2>Sample Lines from JMdict.txt</h2>";

if (file_exists($filePath)) {
    echo "<p>Debug: JMdict.txt file found.</p>";
    $lines = file($filePath);
    echo "<p>Debug: JMdict.txt file contains " . count($lines) . " lines.</p>";
    
    if (count($lines) > 0) {
        echo "<p>Displaying first 10 non-empty lines:</p>";
        echo "<pre>";
        $nonEmptyLines = 0;
        foreach ($lines as $index => $line) {
            $trimmedLine = trim($line);
            if (!empty($trimmedLine)) {
                echo "Line " . ($index + 1) . ": " . htmlspecialchars($trimmedLine) . "\n";
                $nonEmptyLines++;
            }
            if ($nonEmptyLines >= 10) {
                break;
            }
        }
        if ($nonEmptyLines === 0) {
            echo "<p>No non-empty lines found in the first 10 lines.</p>";
        }
        echo "</pre>";
    } else {
        echo "<p>Debug: JMdict.txt file is empty.</p>";
    }
} else {
    echo "<p>Error: JMdict.txt file not found.</p>";
}
?>
