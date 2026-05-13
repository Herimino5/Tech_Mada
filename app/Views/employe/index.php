<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$demandes = is_array($viewData['demandes'] ?? null) ? $viewData['demandes'] : [];
?>
<?= $this->section('page_title') ?>Mes demandes<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil / Mes demandes<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="btn-forest"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="data-card">
  <div class="data-card-head">
    <h3>Toutes mes demandes</h3>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Motif</th></tr>
    </thead>
    <tbody>
      <?php if ($demandes === []): ?>
        <tr>
          <td colspan="7">
            <div class="empty"><i class="bi bi-calendar3"></i><p>Aucune demande enregistrée.</p></div>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($demandes as $demande): ?>
          <?php
          $status = (string) ($demande['statut'] ?? 'en_attente');
          $statusClass = 's-attente';
          if ($status === 'approuvee') {
              $statusClass = 's-approuvee';
          } elseif ($status === 'refusee') {
              $statusClass = 's-refusee';
          } elseif ($status === 'annulee') {
              $statusClass = 's-annulee';
          }
          ?>
          <tr>
            <td><span class="type-badge t-annuel"><?= esc((string) ($demande['type_libelle'] ?? '')) ?></span></td>
            <td class="td-muted"><?= esc((string) ($demande['date_debut'] ?? '')) ?></td>
            <td class="td-muted"><?= esc((string) ($demande['date_fin'] ?? '')) ?></td>
            <td class="td-mono"><?= esc((string) ($demande['nb_jours'] ?? 0)) ?> j</td>
            <td><span class="statut <?= esc($statusClass) ?>"><?= esc($status) ?></span></td>
            <td class="td-muted"><?= esc((string) ($demande['commentaire_rh'] ?? '-')) ?></td>
            <td class="td-muted"><?= esc((string) ($demande['motif'] ?? '-')) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
