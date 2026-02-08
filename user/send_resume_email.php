<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);
    $name = htmlspecialchars($_POST['name']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email address";
        exit;
    }

    $subject = "Resume from $name";
    $message = "
    <html>
    <head>
      <title>Resume Shared</title>
    </head>
    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
      <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
        <h2 style='color: #0d9488;'>You've received a resume!</h2>
        <p><strong>$name</strong> has shared their professional resume with you.</p>
        <p>Click the button below to view it:</p>
        <a href='$link' style='display: inline-block; padding: 12px 24px; background-color: #0d9488; color: white; text-decoration: none; border-radius: 50px; font-weight: bold; margin: 20px 0;'>View Resume</a>
        <p style='font-size: 0.9em; color: #64748b;'>Or copy this link: <br> <a href='$link'>$link</a></p>
        <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
        <p style='font-size: 0.8em; color: #94a3b8;'>Sent via LankaResumey</p>
      </div>
    </body>
    </html>
    ";

    // Headers
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    $headers[] = 'From: LankaResumey <no-reply@lankaresumey.com>';

    if (mail($email, $subject, $message, implode("\r\n", $headers))) {
        echo "Success";
    } else {
        http_response_code(500);
        echo "Failed to send email. Check your server configuration.";
    }
} else {
    http_response_code(403);
    echo "Access Forbidden";
}
?>
