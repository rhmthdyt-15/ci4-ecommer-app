<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mb-3">
                <div class="card-header">
                    <span>Formulir Produk</span>
                </div>
                <div class="card-body">
                    <?= form_open_multipart($form_action) ?>
                    <?= isset($input->id) ? form_hidden('id', $input->id) : '' ?>
                    <div class="form-group">
                        <label for="title">Produk</label>
                        <?= form_input('title', $input->title, ['class' => 'form-control', 'id' => 'title', 'onkeyup' => 'createSlug()']) ?>
                        <?php if(isset($errors['title'])) : ?>
                        <small class="form-text text-danger"><?= $errors['title'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <?= form_textarea('description', $input->description, ['rows' => 4, 'class' => 'form-control']) ?>
                        <?php if(isset($errors['description'])) : ?>
                        <small class="form-text text-danger"><?= $errors['description'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <?= form_input('price', $input->price, ['type' => 'number', 'class' => 'form-control']) ?>
                        <?php if(isset($errors['price'])) : ?>
                        <small class="form-text text-danger"><?= $errors['price'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="id_category">Kategori</label>
                        <?= form_dropdown('id_category', session('categories'), $input->id_category, ['class' => 'form-control']) ?>
                        <?php if(isset($errors['id_category'])) : ?>
                        <small class="form-text text-danger"><?= $errors['id_category'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar</label>
                        <br>
                        <?= form_upload('image') ?>
                        <?php if(isset($errors['image'])) : ?>
                        <small class="form-text text-danger"><?= $errors['image'] ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="is_available">Ada Stock ?</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <?= form_radio('is_available', '1', (property_exists($input, 'is_available') && $input->is_available == 1) ? true : false, ['class' => 'form-check-input']) ?>
                            <label for="is_available" class="form-check-label">Tersedia</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= form_radio('is_available', '0', (property_exists($input, 'is_available') && $input->is_available == 0) ? true : false, ['class' => 'form-check-input']) ?>
                            <label for="is_available" class="form-check-label">Kosong</label>
                        </div>
                        <?= isset($errors['is_available']) ? '<p class="text-danger">'.$errors['is_available'].'</p>' : '' ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</main>



<?= $this->endSection(); ?>