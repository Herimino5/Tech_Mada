<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="data-card">
  <div class="data-card-head">
    <h3>Tableau de bord — Admin</h3>
  </div>
  <div style="padding:1.25rem">
    <p>Bienvenue, <?= esc(session()->get('prenom') . ' ' . session()->get('nom')) ?>.</p>
    <p><a href="<?= site_url('/logout') ?>" class="btn-secondary">Se déconnecter</a></p>
  </div>
</div>
<?= $this->endSection() ?>
