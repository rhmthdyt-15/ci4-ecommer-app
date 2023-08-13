<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
    <meta name="generator" content="Hugo 0.101.0" />
    <title><?= $title; ?> - E-Comerce</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.6/examples/navbar-fixed/" />

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/asset/libs/bootstrap/css/bootstrap.min.css" />

    <link rel="stylesheet" href="/asset/css/app.css" />

    <link rel="stylesheet" href="/asset/libs/fontawesome/css/all.min.css" />

    <!-- Custom styles for this template -->
    <link href="navbar-top-fixed.css" rel="stylesheet" />
</head>

<body>

    <?= $this->include('layouts/navbar'); ?>

    <?= $this->renderSection('content'); ?>

    <script src="/asset/libs/jquery/code.jquery.com_jquery-3.7.0.min.js"></script>
    <script src="/asset/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    function previewImg() {
        const image = document.querySelector('#image');
        const imagelabel = document.querySelector('.custom-file-label');
        const imgPreview = document.querySelector('.img-preview');

        imagelabel.textContent = image.files[0].name;

        const fileImage = new FileReader();
        fileImage.readAsDataURL(image.files[0]);

        fileImage.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
    </script>
</body>

</html>