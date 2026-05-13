<?= $this->extend('layout') ?>

<?= $this->section('page_title') ?>Dashboard RH<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<a href="<?= base_url('rh/dashboard') ?>">RH</a> / <span>Dashboard</span>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h2 style="margin-top: 0; font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1c2b1e;">
    Bienvenue, <?= session()->get('prenom') ?> 👋
</h2>

<!-- MÉTRIQUES STATS -->
<div class="metrics">
    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-amber">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['en_attente'] ?></div>
        <div class="metric-label">Demandes en attente</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-green">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['approuvee'] ?></div>
        <div class="metric-label">Demandes approuvées</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-red">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
        <div class="metric-val"><?= $stats['refusee'] ?></div>
        <div class="metric-label">Demandes refusées</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-blue">
                <i class="bi bi-grid-3x3"></i>
            </div>
        </div>
        <div class="metric-val"><?= ($stats['en_attente'] + $stats['approuvee'] + $stats['refusee']) ?></div>
        <div class="metric-label">Total des demandes</div>
    </div>
</div>

<!-- ACTIONS PRINCIPALES -->
<div class="form-section">
    <h3>Actions rapides</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <a href="<?= base_url('rh/demandes') ?>" class="btn-forest" style="text-align: center; text-decoration: none;">
            <i class="bi bi-inbox"></i>
            Voir toutes les demandes
        </a>
        <a href="<?= base_url('rh/soldes') ?>" class="btn-secondary" style="text-align: center; text-decoration: none;">
            <i class="bi bi-bar-chart"></i>
            Vue des soldes
        </a>
    </div>
</div>

<!-- INFORMATIONS UTILISATEUR -->
<div class="form-section">
    <h3>Mon profil</h3>
    <div class="profile-row">
        <div class="avatar av-blue" style="width: 60px; height: 60px; font-size: 1.5rem;">
            <?= substr(session()->get('prenom'), 0, 1) ?><?= substr(session()->get('nom'), 0, 1) ?>
        </div>
        <div>
            <div class="pname"><?= session()->get('prenom') ?> <?= session()->get('nom') ?></div>
            <div class="pdept" style="margin-top: 4px;">
                <strong>Email :</strong> <?= session()->get('email') ?> <br>
                <strong>Rôle :</strong> Responsable RH
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
