<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Forgot Password<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-8">Reset Password</h2>

        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 text-center">
            Enter your email address and we'll send you a link to reset your password.
        </p>

        <?php if (session('error')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('error') ?></span>
            </div>
        <?php endif ?>

        <?php if (session('message')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('message') ?></span>
            </div>
        <?php endif ?>

        <form method="POST" action="<?= url_to('forgot-password') ?>">
            <?= csrf_field() ?>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="email"
                       type="email"
                       name="email"
                       value="<?= old('email') ?>"
                       required
                       autofocus>
            </div>

            <div class="flex items-center justify-between mb-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                        type="submit">
                    Send Password Reset Link
                </button>
            </div>

            <div class="text-center">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Remember your password?
                    <a class="text-blue-500 hover:text-blue-700" href="<?= url_to('login') ?>">
                        Login here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?> 