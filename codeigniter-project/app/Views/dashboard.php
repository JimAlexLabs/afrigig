<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Welcome, <?= esc(session('name')) ?>!</h2>

                <?php if (session('role') === 'client'): ?>
                    <!-- Client Dashboard -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Posted Jobs</h3>
                            <p class="text-3xl font-bold"><?= count($jobs) ?></p>
                        </div>
                        <div class="bg-green-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Total Bids</h3>
                            <p class="text-3xl font-bold"><?= $total_bids ?></p>
                        </div>
                        <div class="bg-yellow-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Active Jobs</h3>
                            <p class="text-3xl font-bold"><?= count($active_jobs) ?></p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">Your Posted Jobs</h3>
                            <a href="<?= site_url('jobs/create') ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Post New Job
                            </a>
                        </div>

                        <?php if (empty($jobs)): ?>
                            <p class="text-gray-500">You haven't posted any jobs yet.</p>
                        <?php else: ?>
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php foreach ($jobs as $job): ?>
                                        <li>
                                            <a href="<?= site_url('jobs/' . $job['id']) ?>" class="block hover:bg-gray-50">
                                                <div class="px-4 py-4 sm:px-6">
                                                    <div class="flex items-center justify-between">
                                                        <h4 class="text-lg font-medium text-blue-600 truncate">
                                                            <?= esc($job['title']) ?>
                                                        </h4>
                                                        <div class="ml-2 flex-shrink-0 flex">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                <?= $job['status'] === 'open' ? 'bg-green-100 text-green-800' : 
                                                                    ($job['status'] === 'in-progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                                    'bg-gray-100 text-gray-800') ?>">
                                                                <?= ucfirst($job['status']) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 sm:flex sm:justify-between">
                                                        <div class="sm:flex">
                                                            <p class="flex items-center text-sm text-gray-500">
                                                                <span class="truncate">Budget: $<?= number_format($job['budget'], 2) ?></span>
                                                            </p>
                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                            <p>Posted <?= date('M j, Y', strtotime($job['created_at'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php else: ?>
                    <!-- Freelancer Dashboard -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-blue-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Submitted Bids</h3>
                            <p class="text-3xl font-bold"><?= count($bids) ?></p>
                        </div>
                        <div class="bg-green-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Active Jobs</h3>
                            <p class="text-3xl font-bold"><?= count($active_jobs) ?></p>
                        </div>
                        <div class="bg-yellow-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Matching Jobs</h3>
                            <p class="text-3xl font-bold"><?= count($matching_jobs) ?></p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-4">Jobs Matching Your Skills</h3>
                        <?php if (empty($matching_jobs)): ?>
                            <p class="text-gray-500">No matching jobs found. Try updating your skills in your profile.</p>
                        <?php else: ?>
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php foreach ($matching_jobs as $job): ?>
                                        <li>
                                            <a href="<?= site_url('jobs/' . $job['id']) ?>" class="block hover:bg-gray-50">
                                                <div class="px-4 py-4 sm:px-6">
                                                    <div class="flex items-center justify-between">
                                                        <h4 class="text-lg font-medium text-blue-600 truncate">
                                                            <?= esc($job['title']) ?>
                                                        </h4>
                                                        <div class="ml-2 flex-shrink-0 flex">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Open
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 sm:flex sm:justify-between">
                                                        <div class="sm:flex">
                                                            <p class="flex items-center text-sm text-gray-500">
                                                                <span class="truncate">Budget: $<?= number_format($job['budget'], 2) ?></span>
                                                            </p>
                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                            <p>Posted <?= date('M j, Y', strtotime($job['created_at'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-4">Your Active Jobs</h3>
                        <?php if (empty($active_jobs)): ?>
                            <p class="text-gray-500">You don't have any active jobs.</p>
                        <?php else: ?>
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    <?php foreach ($active_jobs as $job): ?>
                                        <li>
                                            <a href="<?= site_url('jobs/' . $job['id']) ?>" class="block hover:bg-gray-50">
                                                <div class="px-4 py-4 sm:px-6">
                                                    <div class="flex items-center justify-between">
                                                        <h4 class="text-lg font-medium text-blue-600 truncate">
                                                            <?= esc($job['title']) ?>
                                                        </h4>
                                                        <div class="ml-2 flex-shrink-0 flex">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                In Progress
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 sm:flex sm:justify-between">
                                                        <div class="sm:flex">
                                                            <p class="flex items-center text-sm text-gray-500">
                                                                <span class="truncate">Budget: $<?= number_format($job['budget'], 2) ?></span>
                                                            </p>
                                                        </div>
                                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                            <p>Started <?= date('M j, Y', strtotime($job['updated_at'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 