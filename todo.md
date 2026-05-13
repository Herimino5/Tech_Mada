
# TODO List : Application de Gestion de Bibliothèque (CodeIgniter 4)

### POUR LANCER LE PROJET VAS DANS
`ETU4362/readme.md`

## 0. Configuration initiale & base de données
- [ ] **Base de données** : Créer la BDD `bibliotheque` en UTF-8.
- [ ] **Tables** :
	- [ ] `livres` : id, titre, auteur, ISBN, annee, categorie, resume, couverture, statut, created_at, updated_at.
	- [ ] `emprunts` : id, livre_id, nom_emprunteur, date_emprunt, date_retour.
- [ ] **Configuration** : Vérifier la connexion dans `app/Config/Database.php` et/ou `.env`.
- [ ] **Sécurité** : Activer le filtre CSRF globalement dans `app/Config/Filters.php`.

---

## 1. Fonctionnalité : Catalogue des livres (recherche et liste paginée)
- [ ] **Route** : `GET /` vers `LivreController::index`.
- [ ] **Model (`LivreModel.php`)** :
	- [ ] Déclarer la table, la clé primaire, les champs autorisés et activer les timestamps.
	- [ ] Ajouter une méthode de recherche avec filtre par mot-clé.
	- [ ] Ajouter une méthode pour la pagination avec 10 livres par page.
- [ ] **Controller (`LivreController.php`)** :
	- [ ] Implémenter `index()`.
	- [ ] Récupérer les paramètres GET (`keyword`, `categorie`).
	- [ ] Appeler le modèle pour récupérer les livres.
	- [ ] Générer la pagination.
	- [ ] Retourner la vue.
- [ ] **Views** :
	- [ ] `app/Views/layout.php` : créer le layout principal avec messages flash et contenu.
	- [ ] `app/Views/livres/index.php` : afficher le formulaire de recherche, la liste des livres et les liens de pagination.

---

## 2. Fonctionnalité : Détails d’un livre
- [ ] **Route** : `GET /livres/(:num)` vers `LivreController::detail`.
- [ ] **Model (`EmpruntModel.php`)** :
	- [ ] Déclarer la table, les champs autorisés et les timestamps.
	- [ ] Ajouter une méthode pour récupérer le dernier emprunt d’un livre.
- [ ] **Controller (`LivreController.php`)** :
	- [ ] Implémenter `detail($id)`.
	- [ ] Récupérer le livre.
	- [ ] Retourner une erreur 404 si le livre n’existe pas.
	- [ ] Récupérer le dernier emprunt.
	- [ ] Retourner la vue.
- [ ] **View (`app/Views/livres/show.php`)** :
	- [ ] Afficher toutes les informations du livre.
	- [ ] Afficher la couverture.
	- [ ] Afficher les informations du dernier emprunt si elles existent.

---

## 3. Fonctionnalité : Ajout d’un livre (formulaire et sauvegarde)
- [ ] **Routes** :
	- [ ] `GET /livres/nouveau` pour afficher le formulaire.
	- [ ] `POST /livres/store` pour traiter l’enregistrement.
- [ ] **Model (`LivreModel.php`)** :
	- [ ] Définir les règles de validation pour titre, auteur, ISBN et année.
	- [ ] Définir les messages d’erreur en français.
- [ ] **Controller (`LivreController.php`)** :
	- [ ] Implémenter `ajouter()`.
	- [ ] Implémenter `enregistrer()`.
	- [ ] Vérifier que l’année n’est pas dans le futur.
	- [ ] Valider l’image de couverture (jpeg/png/webp, max 2 Mo).
	- [ ] Déplacer l’image vers `public/uploads/`.
	- [ ] Insérer le livre en base.
	- [ ] Gérer les erreurs avec `withInput()`.
	- [ ] Rediriger vers l’accueil après succès.
- [ ] **View (`app/Views/livres/create.php`)** :
	- [ ] Ajouter le formulaire avec `csrf_field()`.
	- [ ] Conserver les valeurs avec `old()`.
	- [ ] Afficher les erreurs sous chaque champ.

---

## 4. Fonctionnalité : Suppression d’un livre
- [ ] **Route** : `POST /livres/supprimer/(:num)`.
- [ ] **Controller (`LivreController.php`)** :
	- [ ] Implémenter `supprimer($id)`.
	- [ ] Supprimer le livre par son identifiant.
	- [ ] Rediriger vers le catalogue avec un message flash.
- [ ] **View (`app/Views/livres/index.php`)** :
	- [ ] Ajouter un formulaire POST pour le bouton supprimer.
	- [ ] Ajouter une confirmation JavaScript avant la suppression.

---

## 5. Fonctionnalité : Prêt d’un livre (emprunt)
- [ ] **Route** : `POST /livres/emprunter/(:num)`.
- [ ] **Model (`LivreModel.php` et `EmpruntModel.php`)** :
	- [ ] Enregistrer un emprunt dans `emprunts`.
	- [ ] Mettre à jour le statut du livre à `prete`.
- [ ] **Controller (`EmpruntController.php`)** :
	- [ ] Implémenter `emprunter($id)`.
	- [ ] Vérifier que le livre existe.
	- [ ] Vérifier que le livre est disponible.
	- [ ] Vérifier que le nom de l’emprunteur est fourni.
	- [ ] Créer l’emprunt.
	- [ ] Mettre à jour le statut du livre.
	- [ ] Rediriger avec un message de succès.
- [ ] **View (`app/Views/livres/index.php`)** :
	- [ ] Si le livre est disponible, afficher le champ `nom_emprunteur` et le bouton `Prêter`.

---

## 6. Fonctionnalité : Retour d’un livre
- [ ] **Route** : `POST /livres/retourner/(:num)`.
- [ ] **Model (`LivreModel.php` et `EmpruntModel.php`)** :
	- [ ] Mettre à jour la date de retour sur l’emprunt actif.
	- [ ] Remettre le statut du livre à `disponible`.
- [ ] **Controller (`EmpruntController.php`)** :
	- [ ] Implémenter `retourner($id)`.
	- [ ] Vérifier qu’un emprunt actif existe.
	- [ ] Mettre à jour la date de retour.
	- [ ] Mettre à jour le statut du livre.
	- [ ] Rediriger avec un message de succès.
- [ ] **View (`app/Views/livres/index.php`)** :
	- [ ] Si le livre est prêté, afficher uniquement le bouton `Retourner`.
