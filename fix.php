<?php
echo "<h1>Fixing Image Link...</h1>";

// 1. Define the correct paths
// The folder where your actual images live
$targetFolder = __DIR__ . '/storage/app/public'; 

// The place where the browser looks (Inside the PUBLIC folder)
$linkFolder = __DIR__ . '/public/storage';

echo "Target (Real Images): " . $targetFolder . "<br>";
echo "Link (Shortcut): " . $linkFolder . "<br><br>";

// 2. Check if the real image folder exists
if (!file_exists($targetFolder)) {
    die("<h3 style='color:red'>ERROR: Your storage/app/public folder is missing.</h3>");
}

// 3. Remove any existing broken link in the public folder
if (file_exists($linkFolder)) {
    // If it's a link or file, delete it
    is_link($linkFolder) || is_file($linkFolder) ? unlink($linkFolder) : rmdir($linkFolder);
    echo "Removed old link...<br>";
}

// 4. Create the new link
// We use a relative path for the link because servers prefer it
$relativePath = '../storage/app/public';

if (symlink($relativePath, $linkFolder)) {
    echo "<h2 style='color:green'>SUCCESS!</h2>";
    echo "<p>The link was created inside the 'public' folder.</p>";
    echo "<p><strong>Check your website now.</strong></p>";
} else {
    echo "<h2 style='color:red'>FAILED</h2>";
    echo "<p>Could not create the link. Check permissions.</p>";
}
?>