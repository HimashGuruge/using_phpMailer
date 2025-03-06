<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// ඉල්ලීම POST ආකාරයෙන් ලැබුණාදැයි පරීක්ෂා කරනවා
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ආදාන සනීපාරක්ෂාව (sanitize) කරනවා
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // ඊමේල් ලිපිනය වලංගුදැයි පරීක්ෂා කරනවා
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger text-center">Invalid email address!</div>';
        // අවලංගු නම් ක්‍රියාවලිය නවත්වනවා
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // සර්වර් සැකසුම්
        $mail->SMTPDebug = 0;                    // ඩිබග් ප්‍රකාශන අක්‍රිය කරනවා
        $mail->isSMTP();                        // SMTP භාවිතා කරන බව දක්වනවා
        $mail->Host = 'smtp.gmail.com';         // ඊමේල් සර්වරය
        $mail->SMTPAuth = true;                 // SMTP සත්‍යාපනය සක්‍රිය කරනවා
        $mail->Username = 'himezguruge@gmail.com'; // ඔබේ Gmail ලිපිනය
        $mail->Password = 'cprs wejz nigc vtej';  // Gmail App Password
        $mail->SMTPSecure = 'tls';              // TLS ආරක්ෂණය භාවිතා කරනවා
        $mail->Port = 587;                      // SMTP පෝර්ට් අංකය

        // පළමු ඊමේල්: ඔබේ ලබන්නාගේ ලිපිනයට
        $mail->setFrom('himezguruge@gmail.com', $name); // යවන්නාගේ තොරතුරු
        $mail->addReplyTo($email, $name);       // පිළිතුරු ලිපිනය එකතු කරනවා
        $mail->addAddress('himashguruge1996@gmail.com'); // ලබන්නාගේ ලිපිනය

        $mail->isHTML(true);                    // HTML ආකෘතියෙන් යවන බව දක්වනවා
        $mail->CharSet = 'UTF-8';               // අකුරු කට්ටලය UTF-8 ලෙස සකසනවා
        $mail->Subject = 'New Message from ' . $name; // විෂය පේළිය
        $mail->Body    = '<h3>Name: ' . htmlspecialchars($name) . '</h3>' .
                        '<p>Email: ' . htmlspecialchars($email) . '</p>' .
                        '<p>Message: ' . nl2br(htmlspecialchars($message)) . '</p>'; // HTML ආකෘතික පණිවිඩය
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage: $message"; // සරල පෙළ ආකෘතිය

        $mail->send();                          // ඊමේල් යවනවා

        // ස්වයංක්‍රීය පිළිතුරු සඳහා ලබන්නන් ඉවත් කරනවා
        $mail->clearAllRecipients();

        // පරිශීලකයාට ස්වයංක්‍රීය පිළිතුරු ඊමේල්
        $mail->setFrom('himezguruge@gmail.com', 'Your Website Name'); // යවන්නාගේ තොරතුරු
        $mail->addAddress($email, $name);       // පරිශීලකයාගේ ලිපිනය
        
        $mail->Subject = 'Thank You! - Your Message Was Received'; // විෂය පේළිය
        $mail->Body    = '<h3>Hello ' . htmlspecialchars($name) . ',</h3>' .
                        '<p>Your message was successfully received. We will reply to you soon.</p>' .
                        '<p>Thank you!<br>Your Website</p>'; // HTML ආකෘතික පිළිතුරු පණිවිඩය
        $mail->AltBody = "Hello $name,\n\nYour message was received. We will reply soon.\n\nThank you!\nYour Website"; // සරල පෙළ ආකෘතිය

        $mail->send();                          // ස්වයංක්‍රීය පිළිතුරු යවනවා

        // සාර්ථකත්ව පණිවිඩය සහ පිටුවට යළි-යොමු කිරීම
        echo '<div class="alert alert-success text-center">Message sent successfully!</div>';
        echo '<script>setTimeout(function(){ window.location.href = "index.html"; }, 2000);</script>';
    } catch (Exception $e) {
        // දෝෂයක් ඇති වුවහොත්
        echo '<div class="alert alert-danger text-center">Message sending failed. Error: ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
    }
} else {
    // POST ඉල්ලීමක් නොවේ නම් මුල් පිටුවට යොමු කරනවා
    header("Location: index.html");
    exit;
}
?>