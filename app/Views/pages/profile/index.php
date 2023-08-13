<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $this->include('layouts/menu'); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <?php if ($profile['image']) : ?>
                            <img src="<?= base_url("images/user/{$profile['image']}") ?>" alt="" height="200">
                            <?php else : ?>
                            <img src="<?= base_url("images/user/avatar-default.jpg") ?>" alt="" height="200">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <p>Nama: <?= $profile['name']; ?></p>
                            <p>E-Mail: <?= $profile['email'];?></p>
                            <a href="<?= site_url("profile/edit/{$profile['id']}"); ?>" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>