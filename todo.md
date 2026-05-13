# 🔐 Tâches — Module Authentification (Login)
**Projet : TechMada — Système RH interne**
**Durée estimée : 40 min**

---

## 1. Setup initial

- [ ] Créer les routes d'authentification dans `routes/web.php`
  - `GET /login` → afficher le formulaire
  - `POST /login` → traiter la connexion
  - `POST /logout` → déconnexion
- [ ] Créer le contrôleur `AuthController`

---

## 2. Vue — Formulaire de login

- [ ] Créer la vue `resources/views/auth/login.blade.php`
  - Champ `email`
  - Champ `password`
  - Bouton "Se connecter"
  - Affichage des erreurs de validation
  - Token CSRF (`@csrf`) sur le formulaire POST

---

## 3. Logique de connexion (`AuthController`)

- [ ] Méthode `showLogin()` → retourner la vue login
- [ ] Méthode `login(Request $request)`
  - Valider `email` et `password` (required)
  - Vérifier l'existence de l'employé par email
  - Vérifier le mot de passe avec `password_verify()`
  - Vérifier que le compte est **actif** (`actif = 1`)
  - Démarrer la session CI4 native et stocker :
    - `id`, `nom`, `prenom`, `email`, `role`, `departement_id`
  - Rediriger selon le rôle :
    - `employe` → `/employe/dashboard`
    - `rh` → `/rh/dashboard`
    - `admin` → `/admin/dashboard`
  - En cas d'échec → retour avec message d'erreur (flashdata)
- [ ] Méthode `logout()` → détruire la session et rediriger vers `/login`

---

## 4. Filtre d'authentification (`AuthFilter`)

- [ ] Créer le filtre `app/Filters/AuthFilter.php`
  - Vérifier que la session contient un utilisateur connecté
  - Si non connecté → rediriger vers `/login`
- [ ] Enregistrer le filtre dans `app/Config/Filters.php`
- [ ] Appliquer le filtre sur les 3 groupes de routes protégées :
  - `/employe/*`
  - `/rh/*`
  - `/admin/*`

---

## 5. Vérification du rôle dans les controllers

- [ ] Ajouter une vérification du rôle dans chaque controller (pas dans le filtre)
  - Ex : un employé qui tente d'accéder à `/rh/*` reçoit une erreur 403
- [ ] Créer une méthode helper ou utiliser la session directement

---

## 6. Tests manuels

- [ ] Connexion réussie avec compte `employe` → redirigé vers espace employé
- [ ] Connexion réussie avec compte `rh` → redirigé vers espace RH
- [ ] Connexion réussie avec compte `admin` → redirigé vers back-office
- [ ] Connexion échouée (mauvais mot de passe) → message d'erreur affiché
- [ ] Compte inactif (`actif = 0`) → accès refusé
- [ ] Accès à une route protégée sans être connecté → redirigé vers `/login`
- [ ] Déconnexion → session détruite, retour sur `/login`

---

## Notes techniques (spec CI4)

> - Utiliser **Session CI4 native** (pas `$_SESSION` PHP brut)
> - `password_hash()` obligatoire côté base de données
> - **CSRF activé** sur tous les formulaires POST
> - Pattern PRG : `POST /login` → redirect après succès
> - **Flashdata** CI4 pour les messages d'erreur/succès

---

*Livrables attendus : AuthController fonctionnel + AuthFilter appliqué + vue login propre*