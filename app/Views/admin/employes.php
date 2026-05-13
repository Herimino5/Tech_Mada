<?= $this->extend('layout') ?>
<?php
$viewData = get_defined_vars();
$employes = is_array($viewData['employes'] ?? null) ? $viewData['employes'] : [];
$departements = is_array($viewData['departements'] ?? null) ? $viewData['departements'] : [];
$typesConge = is_array($viewData['typesConge'] ?? null) ? $viewData['typesConge'] : [];
$selectedEmployee = is_array($viewData['selectedEmployee'] ?? null) ? $viewData['selectedEmployee'] : [];
$selectedBalances = is_array($viewData['selectedBalances'] ?? null) ? $viewData['selectedBalances'] : [];
$selectedEmployeeId = (int) ($viewData['selectedEmployeeId'] ?? 0);
$annee = (int) ($viewData['annee'] ?? date('Y'));
$flashErrors = session()->getFlashdata('errors');
$flashErrors = is_array($flashErrors) ? $flashErrors : [];
$selectedBalancesByType = [];
foreach ($selectedBalances as $balance) {
  if (is_array($balance) && isset($balance['type_conge_id'])) {
    $selectedBalancesByType[(int) $balance['type_conge_id']] = $balance;
  }
}
?>
<?= $this->section('page_title') ?>Gestion des employés<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>Accueil / Administration / Employés<?= $this->endSection() ?>
<?= $this->section('topbar_actions') ?>
<a href="#soldes-annuels" class="btn-forest"><i class="bi bi-wallet2"></i> Soldes annuels</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if ($flashErrors !== []): ?>
  <div class="flash flash-error" style="margin-bottom:1rem">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
      <?php foreach ($flashErrors as $error): ?>
        <div><?= esc((string) $error) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<?php if ($selectedEmployee === []): ?>
  <div class="data-card" style="margin-bottom:1rem">
    <div class="data-card-head"><h3>Aucun employé sélectionné</h3></div>
    <div class="empty"><i class="bi bi-person-badge"></i><p>Choisissez un employé dans la liste pour le modifier ou gérer ses soldes.</p></div>
  </div>
<?php else: ?>
  <div class="grid-2" style="margin-bottom:1rem">
    <div class="data-card">
      <div class="data-card-head">
        <h3 id="modification-employe">Modifier l'employé</h3>
      </div>
      <div class="form-section">
        <form method="post" action="<?= site_url('/admin/employes/' . (int) ($selectedEmployee['id'] ?? 0)) ?>" class="form-grid-2">
          <?= csrf_field() ?>
          <input type="hidden" name="annee" value="<?= esc((string) $annee) ?>">
          <div class="f-group">
            <label class="f-label" for="prenom">Prénom</label>
            <input id="prenom" type="text" name="prenom" class="f-input" value="<?= esc(old('prenom', (string) ($selectedEmployee['prenom'] ?? ''))) ?>" required>
          </div>
          <div class="f-group">
            <label class="f-label" for="nom">Nom</label>
            <input id="nom" type="text" name="nom" class="f-input" value="<?= esc(old('nom', (string) ($selectedEmployee['nom'] ?? ''))) ?>" required>
          </div>
          <div class="f-group" style="grid-column:1 / -1">
            <label class="f-label" for="email">Email</label>
            <input id="email" type="email" name="email" class="f-input" value="<?= esc(old('email', (string) ($selectedEmployee['email'] ?? ''))) ?>" required>
          </div>
          <div class="f-group">
            <label class="f-label" for="role">Rôle</label>
            <select id="role" name="role" class="f-select" required>
              <?php foreach (['employe' => 'Employé', 'rh' => 'Responsable RH', 'admin' => 'Administrateur'] as $value => $label): ?>
                <option value="<?= esc($value) ?>" <?= old('role', (string) ($selectedEmployee['role'] ?? 'employe')) === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label" for="departement_id">Département</label>
            <select id="departement_id" name="departement_id" class="f-select">
              <option value="">—</option>
              <?php foreach ($departements as $departement): ?>
                <?php $departementId = (string) ($departement['id'] ?? ''); ?>
                <option value="<?= esc($departementId) ?>" <?= (string) old('departement_id', (string) ($selectedEmployee['departement_id'] ?? '')) === $departementId ? 'selected' : '' ?>><?= esc((string) ($departement['nom'] ?? '')) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="f-group">
            <label class="f-label" for="date_embauche">Date d'embauche</label>
            <input id="date_embauche" type="date" name="date_embauche" class="f-input" value="<?= esc(old('date_embauche', (string) ($selectedEmployee['date_embauche'] ?? ''))) ?>" required>
          </div>
          <div class="f-group">
            <label class="f-label" for="actif">Statut</label>
            <select id="actif" name="actif" class="f-select" required>
              <option value="1" <?= (string) old('actif', (string) ($selectedEmployee['actif'] ?? 1)) === '1' ? 'selected' : '' ?>>Actif</option>
              <option value="0" <?= (string) old('actif', (string) ($selectedEmployee['actif'] ?? 1)) === '0' ? 'selected' : '' ?>>Inactif</option>
            </select>
          </div>
          <div class="f-group" style="grid-column:1 / -1">
            <label class="f-label" for="password">Nouveau mot de passe</label>
            <input id="password" type="password" name="password" class="f-input" placeholder="Laisser vide pour conserver le mot de passe actuel">
          </div>
          <div class="form-actions" style="grid-column:1 / -1">
            <button type="submit" class="btn-forest"><i class="bi bi-check2-circle"></i> Enregistrer les modifications</button>
            <a class="btn-secondary" href="<?= site_url('/admin/employes?edit=' . (int) ($selectedEmployee['id'] ?? 0) . '&annee=' . $annee) ?>">Réinitialiser</a>
          </div>
        </form>
      </div>
    </div>

    <div class="data-card" id="soldes-annuels">
      <div class="data-card-head">
        <h3>Soldes annuels - <?= esc(trim((string) ($selectedEmployee['prenom'] ?? '') . ' ' . (string) ($selectedEmployee['nom'] ?? ''))) ?></h3>
      </div>
      <div class="flash flash-info" style="margin:1rem">
        <i class="bi bi-info-circle-fill"></i>
        <span style="font-size:.82rem">L'initialisation crée les lignes manquantes pour <?= esc((string) $annee) ?> sans écraser les valeurs déjà enregistrées.</span>
      </div>
      <form method="post" action="<?= site_url('/admin/employes/' . (int) ($selectedEmployee['id'] ?? 0) . '/soldes/initialiser') ?>" style="margin:0 1rem 1rem">
        <?= csrf_field() ?>
        <input type="hidden" name="annee" value="<?= esc((string) $annee) ?>">
        <button type="submit" class="btn-secondary"><i class="bi bi-folder-plus"></i> Initialiser les soldes de <?= esc((string) $annee) ?></button>
      </form>
      <form method="post" action="<?= site_url('/admin/employes/' . (int) ($selectedEmployee['id'] ?? 0) . '/soldes') ?>" class="form-section" style="padding-top:0">
        <?= csrf_field() ?>
        <div class="f-group" style="margin:0 1rem 1rem">
          <label class="f-label" for="annee">Année</label>
          <input id="annee" type="number" name="annee" class="f-input" min="2020" max="2099" value="<?= esc((string) $annee) ?>" required>
        </div>
        <table class="tbl" style="margin-top:0">
          <thead>
            <tr><th>Type de congé</th><th>Attribués</th><th>Pris</th><th>Restants</th></tr>
          </thead>
          <tbody>
            <?php foreach ($typesConge as $type): ?>
              <?php
              $typeId = (int) ($type['id'] ?? 0);
              $balance = $selectedBalancesByType[$typeId] ?? [];
              $attribues = old('jours_attribues.' . $typeId, (string) ($balance['jours_attribues'] ?? $type['jours_annuels'] ?? 0));
              $pris = old('jours_pris.' . $typeId, (string) ($balance['jours_pris'] ?? 0));
              $restants = max((int) $attribues - (int) $pris, 0);
              ?>
              <tr>
                <td>
                  <strong><?= esc((string) ($type['libelle'] ?? '')) ?></strong>
                  <div class="td-muted" style="font-size:.78rem">Défaut annuel : <?= esc((string) ($type['jours_annuels'] ?? 0)) ?> j</div>
                </td>
                <td style="width:160px"><input type="number" min="0" name="jours_attribues[<?= esc((string) $typeId) ?>]" class="f-input" value="<?= esc((string) $attribues) ?>"></td>
                <td style="width:160px"><input type="number" min="0" name="jours_pris[<?= esc((string) $typeId) ?>]" class="f-input" value="<?= esc((string) $pris) ?>"></td>
                <td class="td-mono"><?= esc((string) $restants) ?> j</td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="form-actions" style="margin:1rem">
          <button type="submit" class="btn-forest"><i class="bi bi-save2"></i> Enregistrer les soldes</button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<div class="data-card">
  <div class="data-card-head">
    <h3>Tous les employés - soldes <?= esc((string) $annee) ?></h3>
  </div>
  <table class="tbl">
    <thead>
      <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Solde annuel</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php if ($employes === []): ?>
        <tr><td colspan="7"><div class="empty"><i class="bi bi-person-x"></i><p>Aucun employé trouvé.</p></div></td></tr>
      <?php else: ?>
        <?php foreach ($employes as $employe): ?>
          <?php
          $restants = (int) ($employe['total_attribues'] ?? 0) - (int) ($employe['total_pris'] ?? 0);
          ?>
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
            <td><span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest)"><?= esc((string) $restants) ?> / <?= esc((string) ($employe['total_attribues'] ?? 0)) ?> j</span></td>
            <td>
              <div class="action-btns">
                <a class="btn-sm btn-edit" href="<?= site_url('/admin/employes?edit=' . (int) ($employe['id'] ?? 0) . '&annee=' . $annee) ?>"><i class="bi bi-pencil"></i> Éditer</a>
                <a class="btn-sm btn-del" href="<?= site_url('/admin/employes?edit=' . (int) ($employe['id'] ?? 0) . '&annee=' . $annee . '#soldes-annuels') ?>" title="Gérer les soldes"><i class="bi bi-wallet2"></i></a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
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
