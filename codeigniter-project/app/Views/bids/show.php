<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Bid Details - <?= esc($bid['job_title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <?php if (session()->has('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="border-b pb-4 mb-4">
                <h1 class="text-2xl font-bold mb-2">Bid for "<?= esc($bid['job_title']) ?>"</h1>
                <p class="text-gray-600">
                    Submitted by <?= esc($bid['freelancer_name']) ?> on
                    <?= date('M j, Y', strtotime($bid['created_at'])) ?>
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <h3 class="text-gray-600 text-sm font-semibold mb-1">Bid Amount</h3>
                    <p class="text-xl font-bold text-green-600">$<?= number_format($bid['amount'], 2) ?></p>
                </div>
                <div>
                    <h3 class="text-gray-600 text-sm font-semibold mb-1">Delivery Time</h3>
                    <p class="text-xl font-bold"><?= $bid['delivery_time'] ?> days</p>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Proposal</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <?= nl2br(esc($bid['proposal'])) ?>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Status</h3>
                <div class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                    <?php if ($bid['status'] === 'accepted'): ?>
                        bg-green-100 text-green-800
                    <?php elseif ($bid['status'] === 'rejected'): ?>
                        bg-red-100 text-red-800
                    <?php else: ?>
                        bg-yellow-100 text-yellow-800
                    <?php endif; ?>">
                    <?= ucfirst($bid['status']) ?>
                </div>
            </div>

            <?php if (session()->get('user')['role'] === 'client' && $bid['status'] === 'pending'): ?>
                <div class="flex space-x-4">
                    <form action="<?= site_url('bids/' . $bid['id'] . '/accept') ?>" method="post" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Accept Bid
                        </button>
                    </form>

                    <form action="<?= site_url('bids/' . $bid['id'] . '/reject') ?>" method="post" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Reject Bid
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if (session()->get('user')['id'] === $bid['user_id'] && $bid['status'] === 'pending'): ?>
                <form action="<?= site_url('bids/' . $bid['id'] . '/withdraw') ?>" method="post" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit"
                        class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        onclick="return confirm('Are you sure you want to withdraw this bid?')">
                        Withdraw Bid
                    </button>
                </form>
            <?php endif; ?>

            <div class="mt-6">
                <a href="<?= site_url('jobs/' . $bid['job_id']) ?>" class="text-blue-500 hover:text-blue-700">
                    Back to Job
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>