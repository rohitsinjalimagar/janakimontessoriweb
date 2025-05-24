<?php
// Your Cloudflare Turnstile secret key (get this from your Turnstile dashboard)
$secretKey = '0x4AAAAAABecLY91Ug_hHGJ7nOG8tP8F8kU';

// Get the CAPTCHA response token sent from the client
$token = $_POST['cf-turnstile-response'] ?? '';

// Check if token exists
if (empty($token)) {
    die('Captcha token is missing. Please complete the CAPTCHA.');
}

// Prepare data for verification request
$data = [
    'secret' => $secretKey,
    'response' => $token,
    'remoteip' => $_SERVER['REMOTE_ADDR'],  // optional but recommended
];

// Use cURL to POST to Turnstile verify endpoint
$ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$result = json_decode($response, true);

// Check success status
if (isset($result['success']) && $result['success'] === true) {
    // CAPTCHA verification successful
    // You can process form data here safely
    $studentName = htmlspecialchars($_POST['name'] ?? '');
    $classApplying = htmlspecialchars($_POST['class'] ?? '');

    // For demonstration, just print a success message
    echo "Thank you, $studentName. Your application for class $classApplying has been received and verified.";
} else {
    // CAPTCHA verification failed
    echo "CAPTCHA verification failed. Please try again.";
}
?>
