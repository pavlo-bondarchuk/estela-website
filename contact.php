<?php
declare(strict_types=1);

$to = 'contacts@estela-partner.com';
$from = 'no-reply@estela-partner.com';
$siteName = 'Estela LLC';

function clean_text(string $value): string
{
    $value = trim($value);
    $value = str_replace(["\r", "\n"], ' ', $value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_message(string $value): string
{
    $value = trim($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$isPost = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
$sent = false;
$error = '';

if ($isPost) {
    $name = clean_text($_POST['name'] ?? '');
    $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
    $phone = clean_text($_POST['phone'] ?? '');
    $company = clean_text($_POST['company'] ?? '');
    $message = clean_message($_POST['message'] ?? '');
    $captcha = (string) ($_POST['captcha'] ?? '');
    $website = trim((string) ($_POST['website'] ?? ''));

    if ($website !== '') {
        $error = 'Spam protection triggered.';
    } elseif ($name === '' || $email === false || $message === '' || $captcha !== 'yes') {
        $error = 'Please complete the required fields.';
    } else {
        $subject = 'New consultation request from Estela website';
        $body = implode("\n", [
            'New consultation request',
            '',
            'Name: ' . html_entity_decode($name, ENT_QUOTES, 'UTF-8'),
            'Email: ' . $email,
            'Phone: ' . html_entity_decode($phone, ENT_QUOTES, 'UTF-8'),
            'Company: ' . html_entity_decode($company, ENT_QUOTES, 'UTF-8'),
            '',
            'Message:',
            html_entity_decode($message, ENT_QUOTES, 'UTF-8'),
        ]);

        $headers = [
            'From: ' . $siteName . ' <' . $from . '>',
            'Reply-To: ' . $email,
            'Return-Path: ' . $from,
            'Content-Type: text/plain; charset=UTF-8',
        ];

        $sent = mail($to, $subject, $body, implode("\r\n", $headers));

        if (!$sent) {
            $error = 'The message could not be sent. Please email us directly.';
        }
    }
} else {
    header('Location: index.html#contact');
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $sent ? 'Thank You' : 'Request Error'; ?> | Estela LLC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body class="thank-you-page">
    <main class="thank-you-card">
      <a class="brand" href="index.html" aria-label="Estela home">
        <span class="brand-mark">E</span>
        <span>Estela</span>
      </a>

      <?php if ($sent): ?>
        <p class="eyebrow">Request sent</p>
        <h1>Thank you.</h1>
        <p>Your consultation request has been sent. Estela LLC will review your message and contact you soon.</p>
        <a class="button primary" href="index.html">Back to website</a>
      <?php else: ?>
        <p class="eyebrow">Message not sent</p>
        <h1>Something went wrong.</h1>
        <p><?php echo $error; ?> You can also contact us directly at <a href="mailto:contacts@estela-partner.com">contacts@estela-partner.com</a>.</p>
        <a class="button primary" href="index.html#contact">Back to form</a>
      <?php endif; ?>
    </main>
  </body>
</html>
