<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$employes = is_array($viewData['employes'] ?? null) ? $viewData['employes'] : [];
$departements = is_array($viewData['departements'] ?? null) ? $viewData['departements'] : [];
$typesConge = is_array($viewData['typesConge'] ?? null) ? $viewData['typesConge'] : [];
?>
<?= $this->section('page_title') ?>Gestion des employés<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil / Administration / Employés<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<a href="#ajout-employe" class="btn-forest"><i class="bi bi-person-plus"></i> Ajouter</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="form-section" id="ajout-employe">
  <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
  <div class="form-grid-2" style="margin-bottom:1rem">
    <div class="f-group"><label class="f-label">Prénom</label><input type="text" class="f-input" placeholder="Jean"/></div>
    <div class="f-group"><label class="f-label">Nom</label><input type="text" class="f-input" placeholder="Rakoto"/></div>
    <div class="f-group"><label class="f-label">Email</label><input type="email" class="f-input" placeholder="jean.rakoto@techmada.mg"/></div>
    <div class="f-group"><label class="f-label">Mot de passe initial</label><input type="password" class="f-input" placeholder="À communiquer à l'employé"/></div>
    <div class="f-group">
      <label class="f-label">Département</label>
      <select class="f-select">
        <?php foreach ($departements as $departement): ?>
          <option value="<?= esc((string) ($departement['id'] ?? '')) ?>"><?= esc((string) ($departement['nom'] ?? '')) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="f-group">
      <label class="f-label">Rôle</label>
      <select class="f-select">
        <option value="employe">Employé</option>
        <option value="rh">Responsable RH</option>
        <option value="admin">Administrateur</option>
      </select>
    </div>
    <div class="f-group"><label class="f-label">Date d'embauche</label><input type="date" class="f-input" value="2025-06-13"/></div>
  </div>
  <div class="flash flash-info" style="margin-bottom:1rem">
    <i class="bi bi-info-circle-fill"></i>
    <span style="font-size:.82rem">Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.</span>
  </div>
  <div class="form-actions">
    <button class="btn-forest"><i class="bi bi-plus"></i> Créer l'employé</button>
    <button class="btn-secondary">Réinitialiser</button>
  </div>
</div>

<div class="data-card">
  <div class="data-card-head">
    <h3>Tous les employés</h3>
    <div style="display:flex;gap:6px">
      <input type="text" class="f-input" placeholder="Rechercher..." style="width:200px;padding:6px 10px;font-size:.8rem"/>
      <select class="f-select" style="font-size:.8rem;padding:6px 10px;width:auto">
        <option>Tous les départements</option>
        <?php foreach ($departements as $departement): ?>
          <option><?= esc((string) ($departement['nom'] ?? '')) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($employes as $employe): ?>
        <tr>
          <td>
            <div class="profile-row">
              <div class="avatar av-green" style="width:32px;height:32px;font-size:.68rem"><?= esc(strtoupper(substr((string) ($employe['prenom'] ?? ''), 0, 1) . substr((string) ($employe['nom'] ?? ''), 0, 1))) ?></div>
              <div class="profile-info"><div class="pname"><?= esc(trim((string) ($employe['prenom'] ?? '') . ' ' . (string) ($employe['nom'] ?? ''))) ?></div><div class="pdept"><?= esc((string) ($employe['email'] ?? '')) ?></div></div>
            </div>
          </td>
          <td class="td-muted"><?= esc((string) ($employe['departement_nom'] ?? '-')) ?></td>
          <td><span class="type-badge" style="background:#f1efe8;color:#444441"><?= esc((string) ($employe['role'] ?? '')) ?></span></td>
          <td class="td-muted td-mono" style="font-size:.78rem"><?= esc((string) ($employe['date_embauche'] ?? '')) ?></td>
          <td><span class="statut <?= ((int) ($employe['actif'] ?? 0) === 1) ? 's-approuvee' : 's-annulee' ?>" style="font-size:.68rem"><?= ((int) ($employe['actif'] ?? 0) === 1) ? 'actif' : 'inactif' ?></span></td>
          <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)"><?= esc((string) 0) ?> / <?= esc((string) 0) ?> j</span></td>
          <td>
            <div class="action-btns">
              <button class="btn-sm btn-edit"><i class="bi bi-pencil"></i> Éditer</button>
              <button class="btn-sm btn-del"><i class="bi bi-slash-circle"></i></button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="data-card">
  <div class="data-card-head"><h3>Types de congé configurés</h3></div>
  <table class="tbl">
    <thead><tr><th>Libellé</th><th>Jours annuels</th><th>Déductible</th></tr></thead>
    <tbody>
      <?php foreach ($typesConge as $type): ?>
        <tr>
          <td><?= esc((string) ($type['libelle'] ?? '')) ?></td>
          <td class="td-mono"><?= esc((string) ($type['jours_annuels'] ?? 0)) ?></td>
          <td><?= ((int) ($type['deductible'] ?? 0) === 1) ? 'Oui' : 'Non' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?= $this->endSection() ?>
