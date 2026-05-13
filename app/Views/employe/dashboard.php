<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$stats = is_array($viewData['stats'] ?? null) ? $viewData['stats'] : [];
$soldes = is_array($viewData['soldes'] ?? null) ? $viewData['soldes'] : [];
$demandes = is_array($viewData['demandes'] ?? null) ? $viewData['demandes'] : [];
$annee = isset($viewData['annee']) ? (int) $viewData['annee'] : (int) date('Y');
?>
<?= $this->section('page_title') ?>Tableau de bord<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<a href="<?= site_url('/employe/demandes/nouvelle') ?>" class="btn-forest"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="metrics">
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['en_attente'] ?? 0)) ?></div>
    <div class="metric-label">En attente</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['approuvee'] ?? 0)) ?></div>
    <div class="metric-label">Approuvees</div>
  </div>
  <div class="metric">
    <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
    <div class="metric-val"><?= esc((string) ($stats['refusee'] ?? 0)) ?></div>
    <div class="metric-label">Refusees</div>
  </div>
</div>

<div class="data-card">
  <div class="data-card-head"><h3>Mes soldes de conges - <?= esc((string) $annee) ?></h3></div>
  <div style="padding:1rem 1.25rem;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem">
    <?php foreach ($soldes as $solde): ?>
      <?php
      $attribues = (int) ($solde['jours_attribues'] ?? 0);
      $pris = (int) ($solde['jours_pris'] ?? 0);
      $restants = max($attribues - $pris, 0);
      $ratio = $attribues > 0 ? (int) round(($restants / $attribues) * 100) : 0;
      $fillClass = $ratio <= 25 ? 'danger' : ($ratio <= 45 ? 'warn' : '');
      ?>
      <div class="solde-card" style="margin:0">
        <div class="solde-header">
          <span class="solde-type"><?= esc((string) ($solde['libelle'] ?? 'Type conge')) ?></span>
          <span class="solde-nums"><strong><?= esc((string) $restants) ?></strong> / <?= esc((string) $attribues) ?> j</span>
        </div>
        <div class="solde-bar"><div class="solde-fill <?= esc($fillClass) ?>" style="width:<?= esc((string) $ratio) ?>%"></div></div>
        <div class="solde-label"><?= esc((string) $restants) ?> jours restants - <?= esc((string) $pris) ?> pris</div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="data-card">
  <div class="data-card-head">
    <h3>Mes dernieres demandes</h3>
    <a href="<?= site_url('/employe/demandes/nouvelle') ?>" style="font-size:.8rem;color:var(--forest);text-decoration:none">Nouvelle demande -></a>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Type</th><th>Du</th><th>Au</th><th>Duree</th><th>Statut</th><th>Commentaire RH</th></tr>
    </thead>
    <tbody>
      <?php if ($demandes === []): ?>
        <tr>
          <td colspan="6">
            <div class="empty"><i class="bi bi-calendar3"></i><p>Aucune demande pour le moment.</p></div>
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
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
