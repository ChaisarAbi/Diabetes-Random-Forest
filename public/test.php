<?php
echo "PHP is working!<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";

// Check if mod_rewrite is available
if (function_exists('apache_get_modules')) {
    echo "Apache modules: " . implode(', ', apache_get_modules()) . "<br>";
    if (in_array('mod_rewrite', apache_get_modules())) {
        echo "mod_rewrite is ENABLED<br>";
    } else {
        echo "mod_rewrite is NOT ENABLED<br>";
    }
} else {
    echo "Cannot check Apache modules (not running as Apache module)<br>";
}

// Check .htaccess
if (file_exists(__DIR__ . '/.htaccess')) {
    echo ".htaccess file EXISTS<br>";
} else {
    echo ".htaccess file NOT FOUND<br>";
}
?>
