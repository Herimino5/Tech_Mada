<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Bibliothèque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <nav class="mb-4">
        <a href="<?= site_url('/') ?>" class="btn btn-outline-primary">Catalogue</a>
        <a href="<?= site_url('/livres/nouveau') ?>" class="btn btn-outline-success">Ajouter un livre</a>
    </nav>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?> [cite: 336]

    <script>
        function confirmerSuppression() {
            return confirm("Êtes-vous sûr de vouloir supprimer ce livre ?");
        }
    </script>
</body>
</html>