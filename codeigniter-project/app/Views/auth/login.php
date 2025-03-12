<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-8">Welcome Back</h2>

        <?php if (session('error')): ?>
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('error') ?></span>
            </div>
        <?php endif ?>

        <?php if (session('warning')): ?>
            <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('warning') ?></span>
            </div>
        <?php endif ?>

        <?php if (session('message')): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?= session('message') ?></span>
            </div>
        <?php endif ?>

        <form method="POST" action="<?= url_to('login') ?>">
            <?= csrf_field() ?>

            <div class="mb-4">
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

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="password"
                       type="password"
                       name="password"
                       required>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input class="form-checkbox h-4 w-4 text-blue-600"
                           type="checkbox"
                           name="remember"
                           id="remember">
                    <label class="ml-2 text-sm text-gray-600 dark:text-gray-400" for="remember">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a class="text-blue-500 hover:text-blue-700" href="<?= url_to('forgot-password') ?>">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                        type="submit">
                    Sign In
                </button>
            </div>

            <div class="text-center">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Don't have an account?
                    <a class="text-blue-500 hover:text-blue-700" href="<?= url_to('register') ?>">
                        Register here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?> 