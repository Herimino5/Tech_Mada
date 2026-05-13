<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<section class="panel">
    <h3 class="panel__title">Catalogue des livres</h3>

    <form action="<?= site_url('/') ?>" method="get" class="filters">
        <div class="field-group">
            <label for="keyword">Recherche</label>
            <input id="keyword" type="text" name="keyword" placeholder="Titre, auteur ou ISBN" value="<?= esc(request()->getGet('keyword') ?? '') ?>">
        </div>
        <div class="field-group">
            <label for="categorie">Categorie</label>
            <select id="categorie" name="categorie">
                <option value="">Toutes les categories</option>
                <?php foreach (['Roman', 'BD', 'Essai', 'Science', 'Histoire'] as $cat): ?>
                    <option value="<?= esc($cat) ?>" <?= (request()->getGet('categorie') === $cat) ? 'selected' : '' ?>><?= esc($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field-group field-group--button">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
</section>

<section class="books-grid">
    <?php if (! empty($livres)) : ?>
        <?php foreach ($livres as $livre): ?>
            <article class="book-card">
                <a href="<?= site_url('/livres/' . $livre['id']) ?>" class="book-card__cover-link">
                    <?php if (! empty($livre['couverture'])): ?>
                        <img src="<?= base_url('uploads/' . $livre['couverture']) ?>" alt="Couverture de <?= esc($livre['titre']) ?>" class="book-card__cover">
                    <?php else: ?>
                        <div class="book-card__cover-placeholder">Sans image</div>
                    <?php endif; ?>
                </a>

                <div class="book-card__content">
                    <div class="book-card__header">
                        <h4><a href="<?= site_url('/livres/' . $livre['id']) ?>"><?= esc($livre['titre']) ?></a></h4>
                        <span class="status-badge <?= $livre['statut'] === 'disponible' ? 'status-badge--ok' : 'status-badge--warn' ?>">
                            <?= esc(ucfirst($livre['statut'])) ?>
                        </span>
                    </div>

                    <p class="book-card__meta">
                        <?= esc($livre['auteur']) ?> | <?= esc($livre['annee']) ?> | <?= esc($livre['categorie'] ?? '-') ?>
                    </p>

                    <div class="book-card__actions">
                        <a href="<?= site_url('/livres/' . $livre['id']) ?>" class="btn">Voir</a>

                        <?php if ($livre['statut'] === 'disponible'): ?>
                            <form action="<?= site_url('/livres/emprunter/' . $livre['id']) ?>" method="post" class="inline-form">
                                <?= csrf_field() ?>
                                <input type="text" name="nom_emprunteur" placeholder="Nom emprunteur" required>
                                <button type="submit" class="btn btn-success">Preter</button>
                            </form>
                        <?php else: ?>
                            <form action="<?= site_url('/livres/retourner/' . $livre['id']) ?>" method="post" class="inline-form">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-warning">Retourner</button>
                            </form>
                        <?php endif; ?>

                        <form action="<?= site_url('/livres/supprimer/' . $livre['id']) ?>" method="post" class="inline-form" onsubmit="return confirmerSuppression()">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="panel panel--empty">Aucun livre trouve.</div>
    <?php endif; ?>
</section>

<section class="pager-wrap">
    <?= $pager->links() ?>
</section>

<?= $this->endSection() ?>
