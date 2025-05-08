<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php if (isset($title)): ?>
        <title><?= htmlspecialchars($title) ?></title>
    <?php endif; ?>
    <?php
        $baseDir = __DIR__ . '/../../../';
    ?>
    <link href="<?php echo $baseUrl; ?>/public/css/output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif !important;
        }

        .font-sans {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif !important;
        }

        .custom-shadow {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>