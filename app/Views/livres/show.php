<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<section class="detail-grid">
    <aside class="panel detail-side">
        <?php if (! empty($livre['couverture'])): ?>
            <img src="<?= base_url('uploads/' . $livre['couverture']) ?>" alt="Couverture de <?= esc($livre['titre']) ?>" class="livre-cover">
        <?php else: ?>
            <div class="book-card__cover-placeholder livre-cover-placeholder">Sans image</div>
        <?php endif; ?>

        <span class="status-badge <?= $livre['statut'] === 'disponible' ? 'status-badge--ok' : 'status-badge--warn' ?>">
            <?= esc(ucfirst($livre['statut'])) ?>
        </span>

        <?php if ($livre['statut'] === 'disponible'): ?>
            <form action="<?= site_url('/livres/emprunter/' . $livre['id']) ?>" method="post" class="stack-form">
                <?= csrf_field() ?>
                <input type="text" name="nom_emprunteur" placeholder="Nom de l'emprunteur" required>
                <button type="submit" class="btn btn-success">Preter</button>
            </form>
        <?php else: ?>
            <form action="<?= site_url('/livres/retourner/' . $livre['id']) ?>" method="post" class="stack-form">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-warning">Retourner</button>
            </form>
        <?php endif; ?>

        <form action="<?= site_url('/livres/supprimer/' . $livre['id']) ?>" method="post" class="stack-form" onsubmit="return confirmerSuppression()">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

        <a href="<?= site_url('/') ?>" class="btn">Retour au catalogue</a>
    </aside>

    <div class="detail-main">
        <section class="panel">
            <h3 class="panel__title"><?= esc($livre['titre']) ?></h3>
            <div class="meta-grid">
                <p><strong>Auteur :</strong> <?= esc($livre['auteur']) ?></p>
                <p><strong>ISBN :</strong> <?= esc($livre['isbn']) ?></p>
                <p><strong>Annee :</strong> <?= esc($livre['annee']) ?></p>
                <p><strong>Categorie :</strong> <?= esc($livre['categorie'] ?? '-') ?></p>
                <p class="meta-grid__full"><strong>Resume :</strong><br><?= nl2br(esc($livre['resume'] ?? 'Aucun resume.')) ?></p>
            </div>
        </section>

        <section class="panel">
            <h3 class="panel__title">Dernier emprunt</h3>
            <?php if (! empty($emprunt)): ?>
                <ul class="borrow-list">
                    <li><strong>Emprunteur :</strong> <?= esc($emprunt['nom_emprunteur']) ?></li>
                    <li><strong>Date d'emprunt :</strong> <?= esc($emprunt['date_emprunt']) ?></li>
                    <li><strong>Date de retour :</strong> <?= esc($emprunt['date_retour'] ?? 'En cours') ?></li>
                </ul>
            <?php else: ?>
                <p class="muted-text">Aucun emprunt enregistre.</p>
            <?php endif; ?>
        </section>
    </div>
</section>

<?= $this->endSection() ?>