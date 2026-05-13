<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$types = is_array($viewData['types'] ?? null) ? $viewData['types'] : [];
$annee = isset($viewData['annee']) ? (int) $viewData['annee'] : (int) date('Y');
?>
<?= $this->section('page_title') ?>Nouvelle demande de conge<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil / Nouvelle demande<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $errors = session()->getFlashdata('errors'); ?>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">
  <div>
    <div class="form-section">
      <h3>Details de la demande</h3>

      <?php if (is_array($errors) && $errors !== []): ?>
        <div class="flash flash-error">
          <i class="bi bi-exclamation-circle-fill"></i>
          <div>
            <?php foreach ($errors as $error): ?>
              <div><?= esc((string) $error) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= site_url('/employe/demandes') ?>">
        <?= csrf_field() ?>

        <div class="f-group" style="margin-bottom:1rem">
          <label class="f-label">Type de conge <span style="color:var(--danger)">*</span></label>
          <select class="f-select" name="type_conge_id" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $type): ?>
              <?php
              $typeId = (int) ($type['id'] ?? 0);
              $attribues = (int) ($type['jours_attribues'] ?? 0);
              $pris = (int) ($type['jours_pris'] ?? 0);
              $restants = max($attribues - $pris, 0);
              ?>
              <option value="<?= esc((string) $typeId) ?>" <?= old('type_conge_id') == (string) $typeId ? 'selected' : '' ?>>
                <?= esc((string) ($type['libelle'] ?? '')) ?> (<?= esc((string) $restants) ?> j restants)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-grid-2" style="margin-bottom:1rem">
          <div class="f-group">
            <label class="f-label">Date de debut <span style="color:var(--danger)">*</span></label>
            <input type="date" class="f-input" name="date_debut" value="<?= esc((string) old('date_debut')) ?>" required/>
          </div>
          <div class="f-group">
            <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
            <input type="date" class="f-input" name="date_fin" value="<?= esc((string) old('date_fin')) ?>" required/>
          </div>
        </div>

        <div class="f-group" style="margin-bottom:1rem">
          <label class="f-label">Motif (optionnel)</label>
          <textarea class="f-textarea" name="motif" placeholder="Precisez le motif de votre demande si necessaire..."><?= esc((string) old('motif')) ?></textarea>
          <div class="f-hint">Le motif est visible par le responsable RH.</div>
        </div>

        <div class="form-actions">
          <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
          <a href="<?= site_url('/employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
        </div>
      </form>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:1rem">
    <div class="data-card" style="margin:0">
      <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
      <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
        <?php foreach ($types as $type): ?>
          <?php
          $attribues = (int) ($type['jours_attribues'] ?? 0);
          $pris = (int) ($type['jours_pris'] ?? 0);
          $restants = max($attribues - $pris, 0);
          $ratio = $attribues > 0 ? (int) round(($restants / $attribues) * 100) : 0;
          ?>
          <div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
              <span style="font-size:.8rem;color:var(--ink)"><?= esc((string) ($type['libelle'] ?? '')) ?></span>
              <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= esc((string) $restants) ?> j</span>
            </div>
            <div class="solde-bar"><div class="solde-fill" style="width:<?= esc((string) $ratio) ?>%"></div></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="flash flash-info" style="margin:0">
      <i class="bi bi-info-circle-fill"></i>
      <span style="font-size:.8rem">Le solde est deduit uniquement a l'approbation de votre responsable.</span>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
