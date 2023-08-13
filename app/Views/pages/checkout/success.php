<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Checkout Berhasil
                </div>
                <div class="card-body">
                    <h5>Nomor order: <?= $content->invoice; ?></h5>
                    <p>Terima kasih, sudah belanja.</p>
                    <p>Silakan lakukan pembayaran untuk kami bisa proses selanjutnya dengan cara:</p>
                    <ol>
                        <li>Lakukan pembayaran pada rekening <strong>BCA 123456678</strong> a/n PT. CIShop</li>
                        <li>Sertakan keterangan dengan nomor order:
                            <strong><?= $content->invoice; ?></strong>
                        </li>
                        <li>Total pembayaran: <strong>Rp<?= number_format($content->total, 0, ',', ','); ?></strong>
                        </li>
                    </ol>
                    <p>Jika sudah, silakan kirimkan bukti transfer di halaman konfirmasi atau bisa <a
                            href="<?= base_url("/myorder/detail/$row->invoice"); ?>">klik disini</a>!</p>
                    <a href="<?= site_url('/'); ?>" class="btn btn-primary"><i class="fas fa-angle-left"></i>
                        Kembali</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>