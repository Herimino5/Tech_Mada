<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="data-card">
  <div class="data-card-head">
    <h3>Tableau de bord — Admin</h3>
  </div>
  <div style="padding:1.25rem">
    <p>Bienvenue, <?= esc(session()->get('prenom') . ' ' . session()->get('nom')) ?>.</p>
    <form method="post" action="<?= base_url('logout') ?>" style="display:inline">
      <?= csrf_field() ?>
      <button type="submit" class="btn-secondary">Se déconnecter</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>
