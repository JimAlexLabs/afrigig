<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">
                    Welcome back, <?= esc($user['name']) ?>!
                </h2>
                <p class="mt-2 text-gray-600">
                    <?php if ($user['role'] === 'client'): ?>
                        Manage your projects and find talented freelancers.
                    <?php else: ?>
                        Find great projects and showcase your skills.
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if ($user['role'] === 'client'): ?>
            <!-- Client Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Posted Jobs Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Posted Jobs</h3>
                        <div class="text-3xl font-bold text-indigo-600">
                            <?= count($posted_jobs) ?>
                        </div>
                        <p class="text-gray-600 mt-2">Total jobs posted</p>
                    </div>
                </div>

                <!-- Active Jobs Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Active Jobs</h3>
                        <div class="text-3xl font-bold text-green-600">
                            <?= count($active_jobs) ?>
                        </div>
                        <p class="text-gray-600 mt-2">Jobs in progress</p>
                    </div>
                </div>

                <!-- Completed Jobs Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Completed Jobs</h3>
                        <div class="text-3xl font-bold text-blue-600">
                            <?= count($completed_jobs) ?>
                        </div>
                        <p class="text-gray-600 mt-2">Successfully completed</p>
                    </div>
                </div>
            </div>

            <!-- Recent Bids -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Bids</h3>
                    <?php if (empty($recent_bids)): ?>
                        <p class="text-gray-600">No recent bids on your jobs.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Job Title
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Freelancer
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($recent_bids as $bid): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($bid['job_title']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($bid['freelancer_name']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $<?= number_format($bid['amount'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?= $bid['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($bid['status'] === 'accepted' ? 'bg-green-100 text-green-800' :
                                                        'bg-red-100 text-red-800') ?>">
                                                    <?= ucfirst($bid['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="<?= site_url('bids/' . $bid['id']) ?>" class="text-indigo-600 hover:text-indigo-900">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <!-- Freelancer Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Submitted Bids Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Submitted Bids</h3>
                        <div class="text-3xl font-bold text-indigo-600">
                            <?= count($submitted_bids) ?>
                        </div>
                        <p class="text-gray-600 mt-2">Total bids submitted</p>
                    </div>
                </div>

                <!-- Active Jobs Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Active Jobs</h3>
                        <div class="text-3xl font-bold text-green-600">
                            <?= count($active_bids) ?>
                        </div>
                        <p class="text-gray-600 mt-2">Jobs in progress</p>
                    </div>
                </div>

                <!-- Completed Jobs Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Completed Jobs</h3>
                        <div class="text-3xl font-bold text-blue-600">
                            <?= count($completed_jobs) ?>
                        </div>
                        <p class="text-gray-600 mt-2">Successfully completed</p>
                    </div>
                </div>
            </div>

            <!-- Available Jobs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Available Jobs</h3>
                        <a href="<?= site_url('jobs') ?>" class="text-indigo-600 hover:text-indigo-900">
                            View All Jobs
                        </a>
                    </div>
                    <?php if (empty($available_jobs)): ?>
                        <p class="text-gray-600">No jobs available at the moment.</p>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach (array_slice($available_jobs, 0, 3) as $job): ?>
                                <div class="border rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                        <?= esc($job['title']) ?>
                                    </h4>
                                    <p class="text-gray-600 text-sm mb-2">
                                        <?= character_limiter($job['description'], 100) ?>
                                    </p>
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-sm font-semibold text-gray-800">
                                            $<?= number_format($job['budget'], 2) ?>
                                        </span>
                                        <a href="<?= site_url('jobs/' . $job['id']) ?>" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>