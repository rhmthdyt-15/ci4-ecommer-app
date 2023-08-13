<?= $this->extend('layouts/app'); ?>

<?= $this->section('content'); ?>
<main role="main" class="container">
    <?= $this->include('layouts/_alert'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span>Kategori</span>
                    <a href="<?= base_url('user/create'); ?>" class="btn btn-sm btn-secondary">Tambah</a>

                    <div class="float-right">
                        <?= form_open(base_url('user'), ['method' => 'GET']); ?>
                        <div class="input-group">
                            <input class="form-control form-control-sm text-center" type="text" name="keyword"
                                placeholder="Cari">
                            <div class="input-group-append">
                                <button class="btn btn-info btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="<?= base_url('user/reset'); ?>" class="btn btn-info btn-sm">
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
                                <th scope="col">Pengguna</th>
                                <th scope="col">E-Mail</th>
                                <th scope="col">Role</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1 + (6 * ($currentPage - 1)); foreach($user as $row) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td>
                                    <p>
                                    <p>
                                        <?php if ($row['image']) : ?>
                                        <img src="<?= base_url("images/user/{$row['image']}") ?>" alt="" height="50"
                                            width="50">
                                        <?php else : ?>
                                        <img src="<?= base_url("images/user/avatar-default.jpg") ?>" alt="" height="50"
                                            width="50">
                                        <?php endif; ?>
                                        <?= $row['name'] ?>
                                    </p>
                                    </p>
                                </td>
                                <td><?= $row['email']; ?></td>
                                <td><?= $row['role']; ?></td>
                                <td>
                                    <span
                                        class="badge badge-pill <?= $row['is_active'] ? 'badge-primary' : 'badge-warning'; ?>">
                                        <?= $row['is_active'] ? 'Aktif' : 'Tidak Aktif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: inline-block;">
                                        <?php echo form_open("user/edit/{$row['id']}", ['method' => 'GET']); ?>
                                        <button class="btn btn-sm" type="submit">
                                            <i class="fas fa-edit text-info"></i>
                                        </button>
                                        <?php echo form_close(); ?>
                                    </div>

                                    <div style="display: inline-block;">
                                        <?php echo form_open("user/delete/{$row['id']}", ['method' => 'POST']); ?>
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
                    <?= $pager->links('users', 'costum_pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection(); ?>