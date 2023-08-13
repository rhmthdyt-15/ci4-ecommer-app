<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $this->include('layouts/menu'); ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    Konfirmasi Order #<?= $order['invoice']; ?>
                    <div class="float-right">
                        <span class="badge badge-pill badge-info">
                            <span><?= $order['status']; ?></span>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <?= form_open_multipart($form_action, ['method' => 'POST']) ?>
                    <?= form_hidden('id_orders', $order['id']); ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Transaksi</label>
                            <input type="text" class="form-control" value="<?= $order['invoice'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Dari Rekening a/n</label>
                            <input type="text" name="account_name" value="<?= $input->account_name ?>"
                                class="form-control">
                            <?php if (isset($errors['account_name'])) : ?>
                            <small class="form-text text-danger"><?= $errors['account_name'] ?></small>
                            <?php endif ?>
                        </div>
                        <div class="form-group">
                            <label for="">No Rekening</label>
                            <input type="text" name="account_number" value="<?= $input->account_number ?>"
                                class="form-control">
                            <?php if (isset($errors['account_number'])) : ?>
                            <small class="form-text text-danger"><?= $errors['account_number'] ?></small>
                            <?php endif ?>
                        </div>
                        <div class="form-group">
                            <label for="">Sebesar</label>
                            <input type="number" name="nominal" value="<?= $input->nominal ?>" class="form-control">
                            <?php if (isset($errors['nominal'])) : ?>
                            <small class="form-text text-danger"><?= $errors['nominal'] ?></small>
                            <?php endif ?>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <textarea name="note" id="" cols="30" rows="5" class="form-control">-</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Bukti Transfer</label> <br>
                            <input type="file" name="image" id="">
                            <?php if (isset($image_error)) : ?>
                            <small class="form-text text-danger"><?= $image_error ?></small>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Konfirmasi Pembayaran</button>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>