<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TechMada RH — Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
    <style>
        :root {
            --ink: #1c2b1e;
            --forest: #2d5a3d;
            --forest2: #3d7a52;
            --leaf: #5fa876;
            --mint: #d4ede0;
            --cream: #f8f6f1;
            --white: #ffffff;
            --border: #dde8e1;
            --muted: #7a8f80;
            --danger: #c0392b;
            --danger-bg: #fdf0ee;
            --danger-br: #f0b8b2;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--ink);
            color: var(--ink);
            margin: 0;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }

        .geo-bg {
            position: relative;
            overflow: hidden;
        }

        .geo-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(255, 255, 255, .03) 40px),
                repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(255, 255, 255, .03) 40px);
            pointer-events: none;
            z-index: 0;
        }

        .geo-bg > * {
            position: relative;
            z-index: 1;
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--ink);
        }

        .auth-split {
            display: grid;
            grid-template-columns: 1fr 420px;
            max-width: 900px;
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            background: var(--white);
        }

        .auth-left {
            background: var(--forest);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: var(--white);
        }

        .auth-left-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            margin: 0;
            letter-spacing: -.5px;
        }

        .auth-left-brand span {
            display: block;
            margin-top: 4px;
            font-size: .85rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            color: rgba(255, 255, 255, .55);
        }

        .auth-left-text {
            color: rgba(255, 255, 255, .68);
            font-size: .92rem;
            line-height: 1.7;
            margin-top: 2rem;
        }

        .auth-left-text strong {
            display: block;
            margin-bottom: .5rem;
            color: var(--white);
            font-size: 1.25rem;
            font-family: 'Playfair Display', serif;
        }

        .auth-roles {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, .1);
        }

        .auth-roles-title {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, .28);
            margin-bottom: 4px;
        }

        .role-pill {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 8px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .role-pill i {
            color: var(--leaf);
            font-size: 1.1rem;
        }

        .role-pill-name {
            font-size: .8rem;
            font-weight: 500;
            color: var(--white);
        }

        .role-pill-cred {
            font-size: .72rem;
            color: rgba(255, 255, 255, .45);
            font-family: 'DM Mono', monospace;
        }

        .auth-right {
            padding: 2.5rem;
        }

        .auth-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0 0 .25rem;
        }

        .auth-sub {
            font-size: .85rem;
            color: var(--muted);
            margin: 0 0 1.75rem;
        }

        .f-group {
            margin-bottom: 1rem;
        }

        .f-label {
            font-size: .8rem;
            font-weight: 500;
            color: var(--ink);
            margin-bottom: 5px;
            display: block;
        }

        .f-input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: .875rem;
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--ink);
            transition: border-color .15s, box-shadow .15s;
        }

        .f-input:focus {
            border-color: var(--forest);
            box-shadow: 0 0 0 3px rgba(45, 90, 61, .1);
            outline: none;
        }

        .btn-primary {
            background: var(--forest);
            color: var(--white);
            border: none;
            border-radius: 8px;
            padding: 11px 20px;
            font-weight: 500;
            font-size: .9rem;
            cursor: pointer;
            transition: background .15s;
            font-family: 'DM Sans', sans-serif;
            width: 100%;
        }

        .btn-primary:hover {
            background: var(--forest2);
        }

        .flash {
            padding: 11px 14px;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 1.25rem;
            border: 1px solid transparent;
        }

        .flash-error {
            background: var(--danger-bg);
            color: var(--danger);
            border-color: var(--danger-br);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: .8rem;
            color: var(--muted);
        }

        .auth-footer strong {
            color: var(--forest);
        }

        @media (max-width: 860px) {
            .auth-split {
                grid-template-columns: 1fr;
            }

            .auth-left {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

<section class="auth-page geo-bg">
    <div class="auth-split">
        <div class="auth-left">
            <div>
                <p class="auth-left-brand">TechMada RH<span>Gestion des congés</span></p>
                <p class="auth-left-text">
                    <strong>Bienvenue sur votre espace RH.</strong>
                    Gérez vos demandes de congés, consultez votre solde et suivez l'état de vos demandes en temps réel.
                </p>
            </div>

            <div class="auth-roles">
                <div class="auth-roles-title">Comptes de démonstration</div>
                <div class="role-pill">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <div class="role-pill-name">Administrateur</div>
                        <div class="role-pill-cred">admin@techmada.mg · admin123</div>
                    </div>
                </div>
                <div class="role-pill">
                    <i class="bi bi-person-check"></i>
                    <div>
                        <div class="role-pill-name">Responsable RH</div>
                        <div class="role-pill-cred">rh@techmada.mg · rh123</div>
                    </div>
                </div>
                <div class="role-pill">
                    <i class="bi bi-person"></i>
                    <div>
                        <div class="role-pill-name">Employé</div>
                        <div class="role-pill-cred">employe@techmada.mg · emp123</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <h2 class="auth-title">Connexion</h2>
            <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace.</p>

            <?php if ($error = session()->getFlashdata('error')): ?>
                <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i> <?= esc($error) ?></div>
            <?php endif; ?>

            <?php $errors = session()->getFlashdata('errors'); ?>
            <?php if (is_array($errors) && $errors !== []): ?>
                <div class="flash flash-error" style="align-items:flex-start">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <?php foreach ($errors as $message): ?>
                            <div><?= esc((string) $message) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= site_url('/login') ?>">
                <?= csrf_field() ?>

                <div class="f-group">
                    <label class="f-label" for="email">Adresse email</label>
                    <input id="email" type="email" name="email" value="<?= esc(old('email')) ?>" class="f-input" placeholder="vous@techmada.mg" required>
                </div>

                <div class="f-group">
                    <label class="f-label" for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" class="f-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-primary">Se connecter</button>
            </form>

            <div class="auth-footer">
                Système interne <strong>TechMada RH</strong>
            </div>
        </div>
    </div>
</section>

</body>
</html>
