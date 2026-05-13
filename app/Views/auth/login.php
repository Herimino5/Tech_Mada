<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>TechMada — Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* minimal template tokens used for the auth page */
        :root{--ink:#1c2b1e;--forest:#2d5a3d;--cream:#f8f6f1;--white:#fff;--border:#dde8e1}
        body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0}
        .auth-page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
        .auth-split{display:grid;grid-template-columns:1fr 420px;max-width:900px;width:100%;border-radius:12px;overflow:hidden;background:var(--white)}
        .auth-left{background:var(--forest);padding:2.5rem;color:#fff}
        .auth-right{padding:2.5rem}
        .f-label{font-size:.9rem;margin-bottom:.35rem;display:block}
        .f-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.95rem}
        .btn-primary{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:10px 16px}
        .f-error{color:#c0392b;font-size:.9rem;margin-top:.5rem}
    </style>
</head>
<body>

<section class="auth-page">
    <div class="auth-split">
        <div class="auth-left">
            <h1 style="font-family:Playfair Display,serif;margin:0">TechMada RH</h1>
            <p style="opacity:.9;margin-top:.6rem;line-height:1.4">Système interne — gestion des congés</p>
        </div>

        <div class="auth-right">
            <h2 class="auth-title">Connexion</h2>
            <p class="auth-sub">Connectez-vous avec votre compte employé</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php $errors = session()->getFlashdata('errors'); ?>
            <?php if ($errors && is_array($errors)): ?>
                <div class="alert alert-danger"><ul style="margin:0;padding-left:1.2rem">
                    <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
                </ul></div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('/login') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="f-label">Email</label>
                    <input type="email" name="email" value="<?= esc(old('email')) ?>" class="f-input" required />
                </div>

                <div class="mb-3">
                    <label class="f-label">Mot de passe</label>
                    <input type="password" name="password" class="f-input" required />
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn-primary">Se connecter</button>
                </div>
            </form>

        </div>
    </div>
</section>

</body>
</html>
