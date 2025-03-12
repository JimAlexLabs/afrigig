<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white shadow sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-24 w-24">
                        <?php if ($user['avatar']): ?>
                            <img class="h-24 w-24 rounded-full" src="<?= base_url('writable/uploads/avatars/' . $user['avatar']) ?>" alt="Profile picture">
                        <?php else: ?>
                            <img class="h-24 w-24 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=6366f1&color=fff&size=96" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold text-gray-900">
                            <?= esc($user['name']) ?>
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            <?= ucfirst($user['role']) ?>
                        </p>
                        <div class="mt-3">
                            <a href="<?= site_url('profile/edit') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= esc($user['email']) ?></dd>
                    </div>
                    <?php if ($user['phone']): ?>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?= esc($user['phone']) ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['bio']): ?>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Bio</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?= nl2br(esc($user['bio'])) ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($user['role'] === 'freelancer' && $user['skills']): ?>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Skills</dt>
                            <dd class="mt-1">
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach (explode(',', $user['skills']) as $skill): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <?= trim(esc($skill)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900">Statistics</h2>
                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if ($user['role'] === 'client'): ?>
                        <!-- Client Stats -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Jobs Posted</dt>
                                <dd class="mt-1 text-3xl font-semibold text-indigo-600"><?= $stats['total_jobs_posted'] ?></dd>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Jobs</dt>
                                <dd class="mt-1 text-3xl font-semibold text-green-600"><?= $stats['active_jobs'] ?></dd>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bids Received</dt>
                                <dd class="mt-1 text-3xl font-semibold text-blue-600"><?= $stats['total_bids_received'] ?></dd>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Freelancer Stats -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Bids</dt>
                                <dd class="mt-1 text-3xl font-semibold text-indigo-600"><?= $stats['total_bids'] ?></dd>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Jobs</dt>
                                <dd class="mt-1 text-3xl font-semibold text-green-600"><?= $stats['active_jobs'] ?></dd>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">Success Rate</dt>
                                <dd class="mt-1 text-3xl font-semibold text-blue-600"><?= $stats['success_rate'] ?>%</dd>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 