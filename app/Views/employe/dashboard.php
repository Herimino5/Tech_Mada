<?= $this->extend('layout') ?>
<?= $this->section('page_title') ?>Tableau de bord<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<form method="post" action="<?= site_url('/logout') ?>">
  <?= csrf_field() ?>
  <button type="submit" class="btn-secondary"><i class="bi bi-box-arrow-right"></i> Se deconnecter</button>
</form>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="data-card">
  <div class="data-card-head">
    <h3>Tableau de bord — Employé</h3>
  </div>
  <div style="padding:1.25rem">
    <p>Bienvenue, <?= esc(session()->get('prenom') . ' ' . session()->get('nom')) ?>.</p>
  </div>
</div>
<?= $this->endSection() ?>
