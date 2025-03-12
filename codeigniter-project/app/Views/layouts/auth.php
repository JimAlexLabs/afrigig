<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - AfriGig</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Logo -->
        <div class="flex justify-center pt-8">
            <a href="<?= site_url('/') ?>">
                <img src="<?= base_url('images/logo.png') ?>" alt="AfriGig Logo" class="w-32">
            </a>
        </div>

        <!-- Page Content -->
        <main>
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html> 