<?php
require 'connection.php';
$email = $_POST['Email'];
// echo $email;
$randomNumber = mt_rand(100000, 999999);

$htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
</head>
<body>
    <form method="post" action="resetPassword.php">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center">
                    <h1>Cafeteria</h1>
                    <p>Here's your unique code: <strong style="color: red;">$randomNumber</strong></p>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
HTML;

$to = $email;
$subject = "Reset password";
$message = "<html><body>$htmlContent</body></html>";
$headers = "From: kasemsaad756@gmail.com\r\n" .
    "MIME-Version: 1.0\r\n" .
    "Content-Type: text/html; charset=UTF-8\r\n";

// Send email
$mailSent = mail($to, $subject, $message, $headers);

if ($mailSent) {
    echo "Email sent successfully.";
} else {
    echo "Failed to send email.";
}
$db = new db();
try {
    $upd = "resetcode='$randomNumber'";
    $res = $db->update_data("customers", $upd, "email='$email'");
    header("location:resetPassword.php");
} catch (PDOException $e) {
    header("location:resetPassword.php?err=" . $e->getMessage());
}


?>