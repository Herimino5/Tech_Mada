<?= $this->extend('layout') ?>

<?= $this->section('page_title') ?>Vue des soldes<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('rh/dashboard') ?>">RH</a> / <span>Soldes</span>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- FILTRES BONUS -->
<div class="form-section">
    <h3>Filtres</h3>
    <form method="get" class="form-grid-2">
        <div class="f-group">
            <label class="f-label">Département</label>
            <select name="departement" class="f-select">
                <option value="">Tous les départements</option>
                <?php foreach ($departements as $dept): ?>
                    <option value="<?= $dept['id'] ?>" 
                        <?= ($selected_dept == $dept['id']) ? 'selected' : '' ?>>
                        <?= $dept['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="f-group" style="display: flex; gap: 10px; align-items: flex-end;">
            <button type="submit" class="btn-forest" style="flex: 1;">Filtrer</button>
            <a href="<?= base_url('rh/soldes') ?>" class="btn-secondary" style="flex: 1; text-align: center;">Réinitialiser</a>
        </div>
    </form>
</div>

<!-- TABLEAU DES SOLDES -->
<div class="data-card">
    <div class="data-card-head">
        <h3>Soldes de congés — Année <?= $annee ?></h3>
    </div>

    <?php if (empty($soldes)): ?>
        <div class="empty">
            <i class="bi bi-inbox"></i>
            <p>Aucun solde trouvé</p>
        </div>
    <?php else: ?>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Département</th>
                    <th>Type de congé</th>
                    <th>Attribués</th>
                    <th>Pris</th>
                    <th>Restant</th>
                    <th>Progression</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($soldes as $solde): ?>
                    <?php 
                        $restant = $solde['jours_attribues'] - $solde['jours_pris'];
                        $pourcentage = ($solde['jours_pris'] / $solde['jours_attribues']) * 100;
                        $classe_fill = $pourcentage >= 75 ? 'danger' : ($pourcentage >= 50 ? 'warn' : '');
                    ?>
                    <tr>
                        <td class="td-name"><?= $solde['prenom'] ?> <?= $solde['nom'] ?></td>
                        <td class="td-muted"><?= $solde['dept_nom'] ?? '—' ?></td>
                        <td>
                            <span class="type-badge t-annuel"><?= $solde['libelle'] ?></span>
                        </td>
                        <td class="td-mono"><?= $solde['jours_attribues'] ?></td>
                        <td class="td-mono"><?= $solde['jours_pris'] ?></td>
                        <td class="td-mono" style="font-weight: bold; color: <?= ($restant <= 0) ? '#c0392b' : '#2d5a3d' ?>">
                            <?= $restant ?>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="solde-bar" style="flex: 1; min-width: 80px;">
                                    <div class="solde-fill <?= $classe_fill ?>" style="width: <?= min($pourcentage, 100) ?>%"></div>
                                </div>
                                <span style="font-size: 0.75rem; color: #7a8f80; min-width: 30px;">
                                    <?= round($pourcentage) ?>%
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- LÉGENDE -->
<div style="margin-top: 1.75rem; padding: 1rem; background: var(--cream); border-radius: 8px; font-size: 0.85rem; color: #7a8f80;">
    <strong style="color: var(--ink);">Légende :</strong> Chaque barre représente le taux d'utilisation des jours de congé.<br>
    <div style="margin-top: 0.5rem;">
        <span style="display: inline-block; margin-right: 1.5rem;">
            <span style="display: inline-block; width: 12px; height: 12px; background: var(--forest2); border-radius: 2px; margin-right: 5px;"></span>
            Normale (0–50%)
        </span>
        <span style="display: inline-block; margin-right: 1.5rem;">
            <span style="display: inline-block; width: 12px; height: 12px; background: var(--warn); border-radius: 2px; margin-right: 5px;"></span>
            En cours (50–75%)
        </span>
        <span style="display: inline-block;">
            <span style="display: inline-block; width: 12px; height: 12px; background: var(--danger); border-radius: 2px; margin-right: 5px;"></span>
            Critique (≥75%)
        </span>
    </div>
</div>

<?= $this->endSection() ?>
