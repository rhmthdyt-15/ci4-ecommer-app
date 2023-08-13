<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span><?= $title; ?></span>
                </div>
                <div class="card-body">
                    <?= form_open($form_action, ['method' => 'POST']) ?>
                    <?= isset($input['id']) ? form_hidden('id', $input['id']) : '' ?>
                    <div class="form-group">
                        <label for="">Kategori</label>
                        <?= form_input('title', $input['title'], ['class' => 'form-control', 'id' => 'title']); ?>
                        <?php if(isset($validation) && $validation->hasError('title')) : ?>
                        <small class="form-text text-danger">
                            <?php echo $validation->getError('title'); ?>
                        </small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection(); ?>