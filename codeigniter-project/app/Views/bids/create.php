<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Submit Bid - <?= esc($job['title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Submit a Bid for "<?= esc($job['title']) ?>"</h1>

            <?php if (session()->has('errors')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('jobs/' . $job['id'] . '/bids') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-6">
                    <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">
                        Bid Amount (USD)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-600">$</span>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               name="amount" 
                               id="amount" 
                               value="<?= old('amount') ?>" 
                               class="pl-8 w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Client's budget: $<?= number_format($job['budget'], 2) ?></p>
                </div>

                <div class="mb-6">
                    <label for="delivery_time" class="block text-gray-700 text-sm font-bold mb-2">
                        Delivery Time (Days)
                    </label>
                    <input type="number" 
                           min="1" 
                           name="delivery_time" 
                           id="delivery_time" 
                           value="<?= old('delivery_time') ?>" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                </div>

                <div class="mb-6">
                    <label for="proposal" class="block text-gray-700 text-sm font-bold mb-2">
                        Your Proposal
                    </label>
                    <textarea name="proposal" 
                              id="proposal" 
                              rows="8" 
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              required><?= old('proposal') ?></textarea>
                    <p class="text-sm text-gray-500 mt-1">Minimum 50 characters, maximum 2000 characters</p>
                </div>

                <div class="flex items-center justify-between">
                    <a href="<?= site_url('jobs/' . $job['id']) ?>" 
                       class="text-blue-500 hover:text-blue-700">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Submit Bid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 