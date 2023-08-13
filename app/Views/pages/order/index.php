<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span>Order</span>
                    <div class="float-right">
                        <?= form_open(base_url('order'), ['method' => 'GET']); ?>
                        <div class="input-group">
                            <input class="form-control form-control-sm text-center" type="text" name="keyword"
                                placeholder="Cari">
                            <div class="input-group-append">
                                <button class="btn btn-info btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="<?= base_url('order/reset'); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order as $row) : ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url("/order/detail/{$row['invoice']}"); ?>">#<?= $row['invoice']; ?>
                                    </a>
                                </td>
                                <td><?= date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                <td>Rp<?= number_format($row['total'], 0, ',', '.') ?>,-</td>
                                <td>
                                    <?= $row['status']; ?>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <?= $pager->links('orders', 'costum_pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>