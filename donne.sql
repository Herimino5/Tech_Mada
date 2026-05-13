USE bibliotheque;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE emprunts;
TRUNCATE TABLE livres;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO livres (titre, auteur, isbn, annee, categorie, resume, couverture, statut, created_at, updated_at) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupery', '9782070612758', 1943, 'Roman', 'Un conte poetique sur l amitie et la responsabilite.', NULL, 'disponible', NOW(), NOW()),
('L Etranger', 'Albert Camus', '9782070360024', 1942, 'Roman', 'Un classique de la litterature francaise.', NULL, 'prete', NOW(), NOW()),
('Clean Code', 'Robert C. Martin', '9780132350884', 2008, 'Informatique', 'Bonnes pratiques pour ecrire du code maintenable.', NULL, 'disponible', NOW(), NOW()),
('Design Patterns', 'Erich Gamma', '9780201633610', 1994, 'Informatique', 'Catalogue de patrons de conception orientee objet.', NULL, 'prete', NOW(), NOW()),
('Sapiens', 'Yuval Noah Harari', '9780062316097', 2011, 'Histoire', 'Une breve histoire de l humanite.', NULL, 'disponible', NOW(), NOW()),
('Le Rouge et le Noir', 'Stendhal', '9782253004226', 1830, 'Roman', 'Roman d apprentissage et de critique sociale.', NULL, 'disponible', NOW(), NOW()),
('La Horde du Contrevent', 'Alain Damasio', '9782070410156', 2004, 'Science Fiction', 'Une expedition contre le vent absolu.', NULL, 'prete', NOW(), NOW()),
('L Art de la guerre', 'Sun Tzu', '9782080712059', 500, 'Essai', 'Traite strategique ancien applique au management.', NULL, 'disponible', NOW(), NOW()),
('Atomic Habits', 'James Clear', '9780735211292', 2018, 'Developpement Personnel', 'Methodes concretes pour creer de bonnes habitudes.', NULL, 'disponible', NOW(), NOW()),
('Les Miserables', 'Victor Hugo', '9782253096344', 1862, 'Roman', 'Une fresque sociale majeure du XIXe siecle.', NULL, 'prete', NOW(), NOW());

INSERT INTO emprunts (livre_id, nom_emprunteur, date_emprunt, date_retour, created_at, updated_at) VALUES
(2, 'Amina Traore', '2026-04-12', NULL, NOW(), NOW()),
(4, 'Youssef Benali', '2026-04-10', NULL, NOW(), NOW()),
(7, 'Sarah El Idrissi', '2026-04-15', NULL, NOW(), NOW()),
(10, 'Karim Nadir', '2026-04-18', NULL, NOW(), NOW()),
(1, 'Nadia Othmani', '2026-03-20', '2026-03-30', NOW(), NOW()),
(3, 'Mehdi Lahcen', '2026-03-01', '2026-03-18', NOW(), NOW()),
(5, 'Hiba Ait Ali', '2026-02-10', '2026-02-20', NOW(), NOW());
