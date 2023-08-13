<?php 

$session = \Config\Services::session();

$success = $session->getFlashdata('success');
$error = $session->getFlashdata('error');
$warning = $session->getFlashdata('warning');

if ($success) {
    $alert_status = 'alert-success';
    $status = 'Success';
    $message = $success;
}

if ($error) {
    $alert_status = 'alert-danger';
    $status = 'Error';
    $message = $error;
}

if ($warning) {
    $alert_status = 'alert-warnig';
    $status = 'Warning';
    $message = $warning;
}

?>

<?php if($success || $error || $warning) : ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert <?= $alert_status; ?> alert-dimissible fade show" role="alert">
            <strong><?= $status; ?></strong> <?= $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<?php endif ?>