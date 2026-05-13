<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<?php $errors = $errors ?? (session('errors') ?? []); ?>

<section class="panel">
    <h3 class="panel__title">Ajouter un livre</h3>

    <form action="<?= site_url('/livres/store') ?>" method="post" enctype="multipart/form-data" class="form-grid">
            <?= csrf_field() ?>

            <div class="field-group">
                <label for="titre">Titre</label>
                <input id="titre" type="text" name="titre" class="<?= isset($errors['titre']) ? 'input-error' : '' ?>" value="<?= old('titre') ?>">
                <?php if (isset($errors['titre'])): ?><div class="error-text"><?= esc($errors['titre']) ?></div><?php endif; ?>
            </div>

            <div class="field-group">
                <label for="auteur">Auteur</label>
                <input id="auteur" type="text" name="auteur" class="<?= isset($errors['auteur']) ? 'input-error' : '' ?>" value="<?= old('auteur') ?>">
                <?php if (isset($errors['auteur'])): ?><div class="error-text"><?= esc($errors['auteur']) ?></div><?php endif; ?>
            </div>

            <div class="field-group">
                <label for="isbn">ISBN</label>
                <input id="isbn" type="text" name="isbn" class="<?= isset($errors['isbn']) ? 'input-error' : '' ?>" value="<?= old('isbn') ?>">
                <?php if (isset($errors['isbn'])): ?><div class="error-text"><?= esc($errors['isbn']) ?></div><?php endif; ?>
            </div>

            <div class="field-group">
                <label for="annee">Annee</label>
                <input id="annee" type="number" name="annee" class="<?= isset($errors['annee']) ? 'input-error' : '' ?>" max="<?= date('Y') ?>" value="<?= old('annee') ?>">
                <?php if (isset($errors['annee'])): ?><div class="error-text"><?= esc($errors['annee']) ?></div><?php endif; ?>
            </div>

            <div class="field-group">
                <label for="categorie">Categorie</label>
                <input id="categorie" type="text" name="categorie" value="<?= old('categorie') ?>" placeholder="Roman, BD, Essai...">
            </div>

            <div class="field-group field-group--full">
                <label for="resume">Resume</label>
                <textarea id="resume" name="resume" rows="5"><?= old('resume') ?></textarea>
            </div>

            <div class="field-group field-group--full">
                <label for="couverture">Couverture</label>
                <input id="couverture" type="file" name="couverture" class="<?= isset($errors['couverture']) ? 'input-error' : '' ?>" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <div class="field-help">Format JPG, PNG ou WEBP, taille max 2 Mo.</div>
                <?php if (isset($errors['couverture'])): ?><div class="error-text"><?= esc($errors['couverture']) ?></div><?php endif; ?>
            </div>

            <div class="form-actions field-group--full">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= site_url('/') ?>" class="btn">Annuler</a>
            </div>
    </form>
</section>

<?= $this->endSection() ?>