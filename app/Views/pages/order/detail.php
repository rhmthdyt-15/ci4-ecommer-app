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
                    Detail Order #<?= $order['invoice']; ?>
                    <div class="float-right">
                        <span class="badge badge-pill badge-info">
                            <span><?= $order['status']; ?></span>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <p>Tanggal: <?= date('d-m-Y', strtotime($order['created_at'])); ?></p>
                    <p>Nama: <?= $order['name']; ?></p>
                    <p>Telepon : <?= $order['phone']; ?></p>
                    <p>Alamat: <?= $order['address']; ?></p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order_detail as $row) : ?>
                            <tr>
                                <td>
                                    <p> <img src="<?= $row->image ? base_url("images/product/{$row->image}") : base_url("images/product/default.png") ?>"
                                            alt="" height="50">
                                        <strong><?= $row->title ?></strong>
                                    </p>
                                </td>
                                <td class="text-center">Rp<?= number_format($row->price, 0, ',', '.') ; ?>,-</td>
                                <td class="text-center"><?= $row->qty; ?></td>
                                <td class="text-center">Rp<?= number_format($row->subtotal, 0, ',', '.') ; ?>,-</td>
                            </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td class="text-center">
                                    <strong>Rp<?= number_format($totalSubtotal, 0, ',', '.') ; ?>,-</strong>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php if($order['status'] == 'waiting') : ?>
                <div class="card-footer">
                    <form action="<?= base_url("order/update/{$order['invoice']}") ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $order['id'] ?>">
                        <div class="input-group">
                            <select name="status" id="" class="form-control">
                                <option value="waiting" <?= $order['status'] == 'waiting' ? 'selected' : '' ?>>Menunggu
                                    Pembayaran</option>
                                <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Dibayar
                                </option>
                                <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>
                                    Dikirim
                                </option>
                                <option value="cancel" <?= $order['status'] == 'cancel' ? 'selected' : '' ?>>Batal
                                </option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>


            <?php if (isset($order_confirm)) : ?>
            <div class="row mb-3 mt-3">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Bukti Transfer
                        </div>
                        <div class="card-body">
                            <p>No Rekening: <?= $order_confirm->account_number ?></p>
                            <p>Atas Nama: <?= $order_confirm->account_name ?></p>
                            <p>Nominal: Rp<?= number_format($order_confirm->nominal, 0, ',', '.') ?>,-</p>
                            <p>Catatan: <?= $order_confirm->note ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="<?= base_url("/images/confirm/$order_confirm->image") ?>" alt="" height="200">
                </div>
            </div>
            <?php endif ?>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>