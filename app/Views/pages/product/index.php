<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span>Product</span>
                    <a href="<?= base_url('product/create'); ?>" class="btn btn-sm btn-secondary">Tambah</a>

                    <div class="float-right">
                        <?= form_open(base_url('product'), ['method' => 'GET']); ?>
                        <div class="input-group">
                            <input class="form-control form-control-sm text-center" type="text" name="keyword"
                                placeholder="Cari">
                            <div class="input-group-append">
                                <button class="btn btn-info btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="<?= base_url('product/reset'); ?>" class="btn btn-info btn-sm">
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
                                <th scope="col">No</th>
                                <th scope="col">Produk</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0; foreach($content as $row): ?>
                            <tr>
                                <td><?= ++$no; ?></td>
                                <td>
                                    <p>
                                        <?php if ($row['image']) : ?>
                                        <img src="<?= base_url("images/product/{$row['image']}") ?>" alt="" height="50"
                                            width="50">
                                        <?php else : ?>
                                        <img src="<?= base_url("images/product/default.png") ?>" alt="" height="50"
                                            width="50">
                                        <?php endif; ?>
                                        <?= $row['products_title'] ?>
                                    </p>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <i class="fas fa-tags"></i>
                                        <?= $row['category_title']; ?>
                                    </span>
                                </td>
                                <td>Rp.<?= number_format($row['price'], 0, ',', '.') ; ?></td>
                                <td><?= $row['is_available'] ? 'Tersedia' : 'Kosong'; ?></td>
                                <td>
                                    <div style="display: inline-block;">
                                        <?php echo form_open("product/edit/{$row['id']}", ['method' => 'GET']); ?>
                                        <button class="btn btn-sm" type="submit">
                                            <i class="fas fa-edit text-info"></i>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>

                                    <div style="display: inline-block;">
                                        <?php echo form_open("product/delete/{$row['id']}", ['method' => 'POST']); ?>
                                        <button class="btn btn-sm" type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                    <?= $pager->links('products', 'costum_pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>