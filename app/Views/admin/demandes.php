<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$demandes = is_array($viewData['demandes'] ?? null) ? $viewData['demandes'] : [];
$stats = is_array($viewData['stats'] ?? null) ? $viewData['stats'] : [];
?>
<?= $this->section('page_title') ?>Historique des demandes<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Administration / Historique des demandes<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<a href="<?= site_url('/admin/employes') ?>" class="btn-forest"><i class="bi bi-people"></i> Employés</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="metrics">
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-journal-text"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['total'] ?? 0)) ?></div>
    <div class="metric-label">Total demandes</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['en_attente'] ?? 0)) ?></div>
    <div class="metric-label">En attente</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check2-circle"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['approuvee'] ?? 0)) ?></div>
    <div class="metric-label">Approuvées</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['refusee'] ?? 0)) ?></div>
    <div class="metric-label">Refusées</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-slash-circle"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['annulee'] ?? 0)) ?></div>
    <div class="metric-label">Annulées</div>
  </div>
</div>

<div class="data-card">
  <div class="data-card-head">
    <h3>Historique complet des demandes</h3>
  </div>
  <table class="tbl">
    <thead>
      <tr>
        <th>Employé</th>
        <th>Département</th>
        <th>Type</th>
        <th>Début</th>
        <th>Fin</th>
        <th>Durée</th>
        <th>Statut</th>
        <th>Traité par</th>
        <th>Créée le</th>
        <th>Motif / commentaire</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($demandes === []): ?>
        <tr>
          <td colspan="10"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande enregistrée.</p></div></td>
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
          $traitePar = trim((string) ($demande['rh_prenom'] ?? '') . ' ' . (string) ($demande['rh_nom'] ?? ''));
          ?>
          <tr>
            <td>
              <strong><?= esc(trim((string) ($demande['employe_prenom'] ?? '') . ' ' . (string) ($demande['employe_nom'] ?? ''))) ?></strong>
              <div class="td-muted" style="font-size:.78rem"><?= esc((string) ($demande['employe_email'] ?? '')) ?></div>
            </td>
            <td class="td-muted"><?= esc((string) ($demande['departement_nom'] ?? '-')) ?></td>
            <td><span class="type-badge t-annuel"><?= esc((string) ($demande['type_libelle'] ?? '')) ?></span></td>
            <td class="td-muted"><?= esc((string) ($demande['date_debut'] ?? '')) ?></td>
            <td class="td-muted"><?= esc((string) ($demande['date_fin'] ?? '')) ?></td>
            <td class="td-mono"><?= esc((string) ($demande['nb_jours'] ?? 0)) ?> j</td>
            <td><span class="statut <?= esc($statusClass) ?>"><?= esc($status) ?></span></td>
            <td><?= $traitePar !== '' ? esc($traitePar) : '<span class="td-muted">-</span>' ?></td>
            <td class="td-muted td-mono"><?= esc((string) ($demande['created_at'] ?? '')) ?></td>
            <td class="td-muted"><?= esc(trim((string) ($demande['motif'] ?? '-')) . ((string) ($demande['commentaire_rh'] ?? '') !== '' ? ' / ' . (string) $demande['commentaire_rh'] : '')) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>