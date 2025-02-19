<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

 
<?php

$receiving_email_address = 'kayt121102@gmail.com';

if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;

// Sanitize and validate inputs
$name = htmlspecialchars($_POST['name']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($_POST['subject']);
$message = htmlspecialchars($_POST['message']);

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address.']);
    exit;
}

$contact->from_name = $name;
$contact->from_email = $email;
$contact->subject = $subject;


// SMTP Configuration (Crucial for reliable delivery - Fill this in!)
/*  <--- Uncomment and configure the section below. */
$contact->smtp = array(
    'host' => 'smtp.gmail.com',      // Replace with your SMTP host (e.g., 'smtp.sendgrid.net')
    'username' => 'kayt121102@gmail.com', // Replace with your SMTP username
    'password' => 'iwwwdziszwpoltoa', // Replace with your SMTP password OR API KEY
    'port' => '465',                  // Replace with the correct port (usually 587 or 465)
    'secure' => 'ssl'                 // Replace with 'tls' or 'ssl'
);
/// <--- End of SMTP Configuration


$contact->add_message($name, 'From');
$contact->add_message($email, 'Email');
$contact->add_message($message, 'Message', 10);

$result = $contact->send();

if ($result === true) {
    echo json_encode(['success' => 'Email sent successfully!']);
} else {
    http_response_code(500); // Internal Server Error
    // Log the detailed error for debugging (important!)
    error_log("Email sending error: " . $result);  // Log to your PHP error log
    echo json_encode(['error' => 'An error occurred while sending the email. Please try again later.']); // User-friendly message
}

?>