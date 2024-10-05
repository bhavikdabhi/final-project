<?php
// Get the port from the query parameters
$port = isset($_GET['port']) ? intval($_GET['port']) : 8000; // Default to 8000 if not set

// Attempt to get the client's IP address
$ipAddress = $_SERVER['REMOTE_ADDR'];

// Check if the address is IPv6 localhost and convert it to IPv4 localhost
if ($ipAddress === '::1') {
    $ipAddress = '127.0.0.1';
}

// Debugging output
echo "Client IP Address: $ipAddress<br>";
echo "Port: $port<br>";

// Generate the URL for redirection
$redirectUrl = "http://$ipAddress:$port/"; // Change to your actual target page
echo "Redirecting to: $redirectUrl<br>";

// Redirect to the target page
header("Location: $redirectUrl");
exit();
?>
