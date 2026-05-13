<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <?php $path = uri_string(); ?>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="sidebar__brand">
                <h1>Bibliothèque</h1>
                <p>Gestion du catalogue</p>
            </div>

            <nav class="sidebar__nav">
                <a href="<?= site_url('/') ?>" class="nav-link <?= $path === '' ? 'nav-link--active' : '' ?>">Mes livres</a>
                <a href="<?= site_url('/livres/nouveau') ?>" class="nav-link <?= $path === 'livres/nouveau' ? 'nav-link--active' : '' ?>">Ajouter un livre</a>
            </nav>
        </aside>

        <div class="main-area">
            <header class="topbar">
                <h2>Gestion de bibliothèque</h2>
                <p>Catalogue, emprunts et retours</p>
            </header>

            <main class="content">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script>
        function confirmerSuppression() {
            return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');
        }
    </script>
</body>
</html>