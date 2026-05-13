<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$stats = is_array($viewData['stats'] ?? null) ? $viewData['stats'] : [];
$recentRequests = is_array($viewData['recentRequests'] ?? null) ? $viewData['recentRequests'] : [];
$absentsToday = is_array($viewData['absentsToday'] ?? null) ? $viewData['absentsToday'] : [];
$criticalBalances = is_array($viewData['criticalBalances'] ?? null) ? $viewData['criticalBalances'] : [];
?>
<?= $this->section('page_title') ?>Vue d'ensemble<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Administration / Vue d'ensemble<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<a href="<?= site_url('/admin/employes') ?>" class="btn-forest"><i class="bi bi-people"></i> Employés</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="metrics">
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-people"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['activeEmployees'] ?? 0)) ?></div>
    <div class="metric-label">Employés actifs</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['pendingRequests'] ?? 0)) ?></div>
    <div class="metric-label">Demandes en attente</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check2-circle"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['approvedThisMonth'] ?? 0)) ?></div>
    <div class="metric-label">Approuvées ce mois</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-building"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['departments'] ?? 0)) ?></div>
    <div class="metric-label">Départements</div>
  </div>
</div>

<div class="data-card">
  <div class="data-card-head"><h3>Demandes récentes</h3></div>
  <table class="tbl">
    <thead>
      <tr><th>Employé</th><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th></tr>
    </thead>
    <tbody>
      <?php if ($recentRequests === []): ?>
        <tr><td colspan="6"><div class="empty"><i class="bi bi-inbox"></i><p>Aucune demande récente.</p></div></td></tr>
      <?php else: ?>
        <?php foreach ($recentRequests as $request): ?>
          <?php
          $status = (string) ($request['statut'] ?? 'en_attente');
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
            <td><?= esc(trim((string) ($request['prenom'] ?? '') . ' ' . (string) ($request['nom'] ?? ''))) ?></td>
            <td><span class="type-badge t-annuel"><?= esc((string) ($request['type_libelle'] ?? '')) ?></span></td>
            <td class="td-muted"><?= esc((string) ($request['date_debut'] ?? '')) ?></td>
            <td class="td-muted"><?= esc((string) ($request['date_fin'] ?? '')) ?></td>
            <td class="td-mono"><?= esc((string) ($request['nb_jours'] ?? 0)) ?> j</td>
            <td><span class="statut <?= esc($statusClass) ?>"><?= esc($status) ?></span></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="grid-2" style="margin-top:1rem">
  <div class="data-card">
    <div class="data-card-head"><h3>Absents aujourd'hui</h3></div>
    <div class="stack-list">
      <?php if ($absentsToday === []): ?>
        <div class="empty"><i class="bi bi-emoji-smile"></i><p>Aucun absent aujourd'hui.</p></div>
      <?php else: ?>
        <?php foreach ($absentsToday as $row): ?>
          <div class="stack-item">
            <strong><?= esc(trim((string) ($row['prenom'] ?? '') . ' ' . (string) ($row['nom'] ?? ''))) ?></strong>
            <span><?= esc((string) ($row['type_libelle'] ?? '')) ?> — jusqu'au <?= esc((string) ($row['date_fin'] ?? '')) ?></span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="data-card">
    <div class="data-card-head"><h3>Soldes critiques</h3></div>
    <div class="stack-list">
      <?php if ($criticalBalances === []): ?>
        <div class="empty"><i class="bi bi-shield-check"></i><p>Aucun solde critique.</p></div>
      <?php else: ?>
        <?php foreach ($criticalBalances as $row): ?>
          <div class="stack-item">
            <strong><?= esc(trim((string) ($row['prenom'] ?? '') . ' ' . (string) ($row['nom'] ?? ''))) ?></strong>
            <span><?= esc((string) ($row['libelle'] ?? '')) ?> — <strong><?= esc((string) ($row['restants'] ?? 0)) ?></strong> jours restants</span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>