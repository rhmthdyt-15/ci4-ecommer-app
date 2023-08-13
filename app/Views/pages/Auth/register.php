<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    Register
                </div>
                <div class="card-body">
                    <?= form_open('register', ['method' => 'POST']); ?>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <?= form_input('name', set_value('name'), ['class' => 'form-control', 'autofocus' => 'autofocus']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('name') ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <?= form_input('email', set_value('email'), ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Masukan email aktif']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('email') ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <?= form_password('password', set_value('password'), ['type' => 'password', 'class' => 'form-control', 'placeholder' => 'Masukan password minimal 8 karakter']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('password') ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Konfirmasi Password</label>
                        <?= form_password('password_confirmation', '', ['class' => 'form-control', 'placeholder' => 'Masukan password yang sama']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('password_confirmation') ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>