<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$profile = is_array($viewData['profile'] ?? null) ? $viewData['profile'] : [];
?>
<?= $this->section('page_title') ?>Mon profil<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil / Mon profil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="data-card">
  <div class="data-card-head">
    <h3>Modifier mon profil</h3>
  </div>
  <div class="form-section">
    <form method="post" action="<?= site_url('/employe/profil') ?>" class="grid-2">
      <?= csrf_field() ?>
      <div class="f-group">
        <label for="prenom">Prénom</label>
        <input id="prenom" type="text" name="prenom" value="<?= esc(old('prenom', (string) ($profile['prenom'] ?? ''))) ?>" required>
      </div>
      <div class="f-group">
        <label for="nom">Nom</label>
        <input id="nom" type="text" name="nom" value="<?= esc(old('nom', (string) ($profile['nom'] ?? ''))) ?>" required>
      </div>
      <div class="f-group" style="grid-column:1 / -1">
        <label for="email">Adresse email</label>
        <input id="email" type="email" name="email" value="<?= esc(old('email', (string) ($profile['email'] ?? ''))) ?>" required>
      </div>
      <div class="f-group" style="grid-column:1 / -1">
        <label>Département</label>
        <input type="text" value="<?= esc((string) ($profile['departement_nom'] ?? '-')) ?>" disabled>
      </div>
      <div class="f-group" style="grid-column:1 / -1">
        <label>Date d'embauche</label>
        <input type="text" value="<?= esc((string) ($profile['date_embauche'] ?? '-')) ?>" disabled>
      </div>
      <div style="grid-column:1 / -1;display:flex;gap:.75rem;flex-wrap:wrap">
        <button type="submit" class="btn-forest"><i class="bi bi-check2-circle"></i> Enregistrer</button>
        <a href="<?= site_url('/employe/dashboard') ?>" class="btn-ghost">Retour</a>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>