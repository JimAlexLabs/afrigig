<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>Edit Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form action="<?= site_url('profile/update') ?>" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
                <?= csrf_field() ?>

                <div class="px-4 py-5 sm:p-6">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Profile Information</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Update your profile information and avatar.
                            </p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-4">
                                    <label for="avatar" class="block text-sm font-medium text-gray-700">
                                        Profile Picture
                                    </label>
                                    <div class="mt-2 flex items-center">
                                        <?php if ($user['avatar']): ?>
                                            <img class="h-12 w-12 rounded-full" src="<?= base_url('writable/uploads/avatars/' . $user['avatar']) ?>" alt="">
                                        <?php else: ?>
                                            <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>&background=6366f1&color=fff" alt="">
                                        <?php endif; ?>
                                        <div class="ml-4">
                                            <div class="relative bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm">
                                                <input type="file" id="avatar" name="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                                <span class="text-sm text-gray-700">Change</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if (session('errors.avatar')): ?>
                                        <p class="mt-2 text-sm text-red-600"><?= session('errors.avatar') ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="<?= old('name', $user['name']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <?php if (session('errors.name')): ?>
                                        <p class="mt-2 text-sm text-red-600"><?= session('errors.name') ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                    <input type="email" name="email" id="email" value="<?= old('email', $user['email']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <?php if (session('errors.email')): ?>
                                        <p class="mt-2 text-sm text-red-600"><?= session('errors.email') ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="col-span-6 sm:col-span-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
                                    <input type="tel" name="phone" id="phone" value="<?= old('phone', $user['phone']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <?php if (session('errors.phone')): ?>
                                        <p class="mt-2 text-sm text-red-600"><?= session('errors.phone') ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if ($user['role'] === 'freelancer'): ?>
                                    <div class="col-span-6">
                                        <label for="skills" class="block text-sm font-medium text-gray-700">Skills (comma separated)</label>
                                        <input type="text" name="skills" id="skills" value="<?= old('skills', $user['skills']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="e.g. PHP, JavaScript, UI Design">
                                        <?php if (session('errors.skills')): ?>
                                            <p class="mt-2 text-sm text-red-600"><?= session('errors.skills') ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="col-span-6">
                                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                    <div class="mt-1">
                                        <textarea id="bio" name="bio" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= old('bio', $user['bio']) ?></textarea>
                                    </div>
                                    <?php if (session('errors.bio')): ?>
                                        <p class="mt-2 text-sm text-red-600"><?= session('errors.bio') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-2 text-sm text-gray-500">Brief description for your profile.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <a href="<?= site_url('profile') ?>" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 