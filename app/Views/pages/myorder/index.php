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
                    <?= $title; ?>
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
                            <?php foreach($content as $row) : ?>
                            <tr>
                                <td>
                                    <a
                                        href="<?= base_url("/myorder/detail/$row->invoice"); ?>">#<?= $row->invoice; ?></a>
                                </td>
                                <td>
                                    <?= date('d-m-Y', strtotime($row->created_at)); ?>
                                </td>
                                <td>Rp<?= number_format($row->total, 0, ',', '.') ?>,-</td>
                                <td>
                                    <?= $row->status; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>