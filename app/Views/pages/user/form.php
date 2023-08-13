<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span><?= $title; ?></span>
                </div>
                <div class="card-body">
                    <?= form_open_multipart($form_action) ?>
                    <?= csrf_field() ?>

                    <?= isset($input->id) ? form_hidden('id', $input->id) : '' ?>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <?= form_input('name', $input->name, ['class' => 'form-control', 'autofocus' => 'autofocus']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?= $validation->getError('name') ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">E-Mail</label>
                        <?= form_input('email', $input->email, ['type' => 'email', 'class' => 'form-control', 'placeholder' => 'Masukan email aktif']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?= $validation->getError('email') ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <?= form_password('password', '', ['class' => 'form-control', 'placeholder' => 'Masukan password minimal 8 karakter']); ?>
                        <?php if (isset($validation)) : ?>
                        <small class="form-text text-danger">
                            <?= $validation->getError('password') ?>
                        </small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <?= form_dropdown('role', $roles, $input->role, ['class' => 'form-control']) ?>
                        <?php if(isset($errors['role'])) : ?>
                        <small class="form-text text-danger"><?= $errors['role'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="is_active">Status</label>
                        <?= form_dropdown('is_active', ['1' => 'Aktif', '0' => 'Tidak Aktif'], $input->is_active, ['class' => 'form-control']) ?>
                        <?php if(isset($errors['is_active'])) : ?>
                        <small class="form-text text-danger"><?= $errors['is_active'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8">
                            <div class="custom-file">
                                <?php if (isset($input->image) && $input->image && file_exists(FCPATH . 'images/user/' . $input->image)) : ?>
                                <img src="<?= base_url('images/user/' . $input->image) ?>" alt="" height="100"
                                    class="img-preview">
                                <?php else : ?>
                                <img src="<?= base_url('images/user/avatar-default.jpg') ?>" alt="Placeholder Image"
                                    height="100" class="img-preview">
                                <?php endif; ?>
                                <?php if (isset($errors['image'])) : ?>
                                <small class="form-text text-danger"><?= $errors['image'] ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="custom-file mt-5">
                            <input type="file" name="image" id="image" class="custom-file-input"
                                onchange="previewImg()">
                            <label for="image" class="custom-file-label col-sm-3 col-form-label">Gambar</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>