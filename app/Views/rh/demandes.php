<?= $this->extend('layout') ?>

<?= $this->section('page_title') ?>Demandes de congés<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('rh/dashboard') ?>">RH</a> / <span>Demandes</span>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Messages flash -->
<?php if (session()->has('success')): ?>
    <div class="flash flash-success">
        <i class="bi bi-check-circle"></i>
        <?= session('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="flash flash-error">
        <i class="bi bi-exclamation-circle"></i>
        <?= session('error') ?>
    </div>
<?php endif; ?>

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
            <a href="<?= base_url('rh/demandes') ?>" class="btn-secondary" style="flex: 1; text-align: center;">Réinitialiser</a>
        </div>
    </form>
</div>

<!-- TABLEAU DES DEMANDES -->
<div class="data-card">
    <div class="data-card-head">
        <h3>Demandes en attente</h3>
        <span class="nav-badge"><?= count($demandes) ?></span>
    </div>

    <?php if (empty($demandes)): ?>
        <div class="empty">
            <i class="bi bi-inbox"></i>
            <p>Aucune demande en attente</p>
        </div>
    <?php else: ?>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Département</th>
                    <th>Type</th>
                    <th>Dates</th>
                    <th>Jours</th>
                    <th>Motif</th>
                    <th>Envoyée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td class="td-name"><?= $demande['prenom'] ?> <?= $demande['nom'] ?></td>
                        <td class="td-muted"><?= $demande['dept_nom'] ?? '—' ?></td>
                        <td>
                            <span class="type-badge t-annuel"><?= $demande['type_conge'] ?></span>
                        </td>
                        <td class="td-mono">
                            <?= date('d/m/Y', strtotime($demande['date_debut'])) ?> 
                            <br>
                            à
                            <br>
                            <?= date('d/m/Y', strtotime($demande['date_fin'])) ?>
                        </td>
                        <td class="td-mono"><?= $demande['nb_jours'] ?> j</td>
                        <td class="td-muted"><?= substr($demande['motif'] ?? '', 0, 30) ?>...</td>
                        <td class="td-muted"><?= date('d/m à H:i', strtotime($demande['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <!-- Bouton Approuver -->
                                <form method="post" action="<?= base_url('rh/demandes/' . $demande['id'] . '/approuver') ?>" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-sm btn-approve" onclick="return confirm('Approuver cette demande ?')">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>

                                <!-- Bouton Refuser (avec modal) -->
                                <button class="btn-sm btn-refuse" onclick="openRefuseModal(<?= $demande['id'] ?>, '<?= htmlspecialchars($demande['prenom']) ?> <?= htmlspecialchars($demande['nom']) ?>')">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- MODAL REFUSER DEMANDE -->
<div id="refuseModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 450px; width: 90%;">
        <h3 style="margin: 0 0 1rem; font-family: 'Playfair Display', serif; font-size: 1.1rem;">Refuser la demande</h3>
        
        <p id="refuseEmployeName" style="margin: 0 0 1.5rem; color: #7a8f80; font-size: 0.9rem;"></p>

        <form id="refuseForm" method="post">
            <?= csrf_field() ?>
            
            <div class="f-group">
                <label class="f-label">Commentaire (optionnel)</label>
                <textarea name="commentaire_rh" class="f-textarea" placeholder="Explicitez le motif du refus..."></textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 1.5rem;">
                <button type="button" class="btn-secondary" style="flex: 1;" onclick="closeRefuseModal()">Annuler</button>
                <button type="submit" class="btn-refuse" style="flex: 1;">Refuser</button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Refuser
function openRefuseModal(demandeId, employeName) {
    document.getElementById('refuseEmployeName').textContent = `Refuser la demande de ${employeName}`;
    document.getElementById('refuseForm').action = `<?= base_url('rh/demandes') ?>/${demandeId}/refuser`;
    document.getElementById('refuseModal').style.display = 'flex';
}

function closeRefuseModal() {
    document.getElementById('refuseModal').style.display = 'none';
}

// Fermer le modal au clic en dehors
document.getElementById('refuseModal').addEventListener('click', function(e) {
    if (e.target === this) closeRefuseModal();
});
</script>

<?= $this->endSection() ?>
