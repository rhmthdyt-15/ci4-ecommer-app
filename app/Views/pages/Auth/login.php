<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <?= form_open('login', ['method' => 'POST']); ?>
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <?= form_input('email', set_value('email'), ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Masukan email aktif']); ?>
                        <?php if (isset($validation) && $validation->hasError('email')) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('email'); ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <?= form_password('password', set_value('password'), ['type' => 'password', 'class' => 'form-control', 'placeholder' => 'Masukan password minimal 8 karakter']); ?>
                        <?php if (isset($validation) && $validation->hasError('password')) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('password'); ?>
                        </small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Login</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>