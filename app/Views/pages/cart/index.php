<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
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
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($content->getResult() as $row) : ?>
                            <tr>
                                <td>
                                    <img src="<?= $row->image ? base_url("images/product/{$row->image}") : base_url("images/product/default.png") ?>"
                                        alt="" height="50">
                                    <strong><?= $row->product_title ?></strong>
                                </td>
                                <td class="text-center">Rp<?= number_format($row->price, 0, ',', '.') ; ?>,-</td>
                                <td>
                                    <form action="<?= site_url("cart/update/$row->id"); ?>" method="post">
                                        <input type="hidden" name="id" value="<?= $row->id; ?>">
                                        <div class="input-group">
                                            <input type="number" name="qty" class="form-control text-center"
                                                value="<?= $row->qty; ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-info" type="submit"><i
                                                        class="fas fa-check"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">Rp<?= number_format($row->subtotal, 0, ',', '.') ; ?>,-</td>
                                <td>
                                    <form action="<?= site_url('cart/delete/' . $row->id) ?>" method="POST">
                                        <button class="btn btn-danger" type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td colspan="3">
                                    <strong>Rp<?= number_format($totalSubtotal, 0, ',', '.') ; ?>,-</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="<?= site_url('checkout'); ?>" class="btn btn-success float-right">Pembayaran <i
                            class="fas fa-angle-right"></i></a>
                    <a href="<?= site_url('/'); ?>" class="btn btn-warning text-white"><i class="fas fa-angle-left"></i>
                        Kembali
                        Belanja</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>