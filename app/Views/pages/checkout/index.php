<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            Alamat Pengiriman
                        </div>
                        <div class="card-body">
                            <form action="<?= site_url('checkout/create'); ?>" method="POST">
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Masukkan nama penerima">
                                    <?php if (isset($validation) && $validation->hasError('name')) : ?>
                                    <div class="text-danger"><?= $validation->getError('name'); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea class="form-control" name="address"
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                    <?php if (isset($validation) && $validation->hasError('address')) : ?>
                                    <div class="text-danger"><?= $validation->getError('address'); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="text" class="form-control" name="phone"
                                        placeholder="Masukkan nomor telepon">
                                    <?php if (isset($validation) && $validation->hasError('phone')) : ?>
                                    <div class="text-danger"><?= $validation->getError('phone'); ?></div>
                                    <?php endif; ?>
                                </div>

                                <button type="submit" class="btn btn-primary">Checkout</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            Cart
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $row) : ?>
                                    <tr>
                                        <td>
                                            <?= $row['title']; ?>
                                        </td>
                                        <td><?= $row['qty']; ?></td>
                                        <td>Rp<?= number_format($row['price'], 0, ',', '.'); ?>,-</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Subtotal</td>
                                        <td>Rp<?= number_format($row['subtotal'], 0, ',', '.'); ?>,-</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th><?= number_format($totalSubtotal, 0, ',', '.') ; ?>,-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>