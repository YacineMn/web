
DROP DATABASE IF EXISTS site_recettes;
CREATE DATABASE site_recettes;
USE site_recettes;

CREATE TABLE recette (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    etapes TEXT,
    photo VARCHAR(255)
);

CREATE TABLE ingredient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE recette_ingredient (
    id_recette INT NOT NULL,
    id_ingredient INT NOT NULL,
    quantite VARCHAR(100),
    PRIMARY KEY (id_recette, id_ingredient),
    FOREIGN KEY (id_recette) REFERENCES recette(id) ON DELETE CASCADE,
    FOREIGN KEY (id_ingredient) REFERENCES ingredient(id) ON DELETE CASCADE
);

CREATE TABLE recette_tag (
    id_recette INT NOT NULL,
    id_tag INT NOT NULL,
    PRIMARY KEY (id_recette, id_tag),
    FOREIGN KEY (id_recette) REFERENCES recette(id) ON DELETE CASCADE,
    FOREIGN KEY (id_tag) REFERENCES tag(id) ON DELETE CASCADE
);

INSERT INTO recette (titre, description, etapes, photo) VALUES
('Spaghetti bolognaise',
 'Pates avec sauce tomate et viande',
 '1. Faire cuire les spaghetti dans une grande casserole d eau bouillante salee. 
  2. Emincer l oignon et hacher l ail. 
  3. Faire revenir l oignon et l ail dans une poele avec un peu d huile. 
  4. Ajouter la viande hachee et la faire cuire jusqu a ce qu elle soit bien doree. 
  5. Verser la sauce tomate et melanger. 
  6. Laisser mijoter a feu doux pendant 15 a 20 minutes. 
  7. Egoutter les pates une fois cuites. 
  8. Servir les spaghetti avec la sauce bolognaise. 
  9. Ajouter du parmesan si souhaite.',
 'images/spagethi_blo.jpeg'),

('Salade César',
 'Salade fraiche avec poulet et parmesan',
 '1. Laver et couper la salade. 
  2. Couper le poulet en morceaux. 
  3. Faire cuire le poulet dans une poele avec un peu d huile. 
  4. Couper le fromage en copeaux. 
  5. Melanger la salade avec le poulet. 
  6. Ajouter la sauce Cesar. 
  7. Ajouter le parmesan par-dessus. 
  8. Servir frais.',
 'images/salade_cesar.jpeg.jpeg'),

('Omelette fromage',
 'Omelette rapide et simple',
 '1. Casser les oeufs dans un bol. 
  2. Battre les oeufs avec une fourchette. 
  3. Ajouter le fromage rape. 
  4. Chauffer une poele avec un peu de beurre. 
  5. Verser le melange dans la poele. 
  6. Laisser cuire quelques minutes. 
  7. Replier l omelette. 
  8. Servir chaud.',
 'images/Omelette.jpeg'),

('Pancakes',
 'Pancakes moelleux',
 '1. Melanger la farine, le sucre et les oeufs dans un bol. 
  2. Ajouter le lait progressivement. 
  3. Melanger jusqu a obtenir une pate lisse. 
  4. Chauffer une poele avec un peu d huile. 
  5. Verser une petite quantite de pate. 
  6. Cuire jusqu a apparition de bulles. 
  7. Retourner le pancake. 
  8. Cuire l autre cote. 
  9. Repeter pour le reste de la pate.',
 'images/Pancakes .jpeg'),

('Smoothie banane',
 'Boisson fruitee',
 '1. Eplucher les bananes. 
  2. Couper les bananes en morceaux. 3. Mettre les morceaux dans un blender. 
  4. Ajouter le yaourt ou le lait. 5. Ajouter un peu de sucre si souhaite. 
  6. Mixer jusqu a obtenir une texture lisse. 
  7. Verser dans un verre. 
  8. Servir frais.',
 'images/Smothie_bananes.jpeg'),

('Tarte aux pommes',
 'Dessert classique aux pommes',
 '1. Prechauffer le four a 180 degres. 
  2. Eplucher et couper les pommes en fines tranches. 
  3. Etaler la pate dans un moule. 
  4. Disposer les pommes sur la pate. 
  5. Ajouter du sucre sur les pommes. 
  6. Mettre au four pendant environ 30 minutes. 
  7. Laisser refroidir. 
  8. Servir.',
 'images/tarte_pommes.jpeg'),

('Crepes maison',
 'Crepes simples et rapides',
 '1. Dans un saladier, verser la farine et faire un puits. 
  2. Ajouter les oeufs puis commencer a melanger doucement. 
  3. Verser le lait progressivement tout en melangeant pour eviter les grumeaux. 
  4. Ajouter une pincee de sel et un peu de sucre si souhaite. 
  5. Melanger jusqu a obtenir une pate lisse et fluide. 
  6. Laisser reposer la pate 20 a 30 minutes. 
  7. Chauffer une poele legerement huilee. 
  8. Verser une louche de pate et repartir sur toute la surface. 
  9. Cuire environ 1 minute de chaque cote. 
  10. Repeter jusqu a epuisement de la pate.',
 'images/crepes.jpeg'),

('Mousse au chocolat',
 'Dessert gourmand au chocolat',
 '1. Faire fondre le chocolat au bain-marie ou au micro-ondes. 
 2. Separer les blancs des jaunes d oeufs. 
 3. Melanger les jaunes d oeufs avec le chocolat fondu. 
 4. Monter les blancs en neige. 
 5. Incorporer delicatement les blancs au melange chocolat. 6. Melanger doucement pour garder une texture legere. 7. Verser la preparation dans des ramequins. 8. Mettre au refrigerateur pendant au moins 2 heures. 9. Servir frais.',
 'images/mousse_chocolat.jpeg'),

('Pizza margherita',
 'Pizza tomate mozzarella',
 '1. Prechauffer le four a 200 degres. 
  2. Etaler la pate a pizza sur une plaque. 
  3. Etaler la sauce tomate sur la pate. 
  4. Couper la mozzarella en morceaux. 
  5. Repartir la mozzarella sur la pizza. 
  6. Ajouter un filet d huile d olive. 
  7. Mettre la pizza au four pendant 10 a 15 minutes. 
  8. Sortir du four quand la pate est doree. 
  9. Servir chaud.',
 'images/pizza_margherita.jpeg'),

('Gratin dauphinois',
 'Gratin fondant aux pommes de terre',
 '1. Prechauffer le four a 180 degres. 
  2. Eplucher et couper les pommes de terre en fines rondelles. 
  3. Frotter un plat avec une gousse d ail. 
  4. Disposer les pommes de terre dans le plat. 
  5. Ajouter la creme fraiche, le sel et le poivre. 
  6. Bien melanger ou repartir uniformement. 
  7. Mettre au four pendant environ 45 minutes. 
  8. Verifier la cuisson avec un couteau. 
  9. Servir chaud.',
 'images/gratin_dauphinois.jpeg'),

('Couscous',
 'Plat principal traditionnel',
 '1. Preparer les legumes. 
  2. Eplucher et couper les legumes en morceaux. 
  3. Faire chauffer un peu d huile dans une grande marmite. 
  4. Ajouter les legumes et les faire revenir. 
  5. Ajouter de l eau et les epices. 
  6. Laisser cuire a feu moyen pendant 30 minutes. 
  7. Preparer la semoule selon les instructions. 
  8. Egoutter si necessaire. 
  9. Servir la semoule avec les legumes et le bouillon.',
 'images/couscous.jpeg'),

('Wrap thon salade',
 'Wrap rapide et frais',
 '1. Egoutter le thon. 
  2. Laver et couper la salade. 
  3. Couper la tomate en morceaux. 
  4. Melanger le thon avec un peu de sauce.
  5. Deposer la garniture sur une galette ou un wrap. 
  6. Ajouter la salade et la tomate. 
  7. Rouler le wrap. 8. Couper en deux.
  9. Servir frais.',
 'images/wrap_thon.jpeg'),

('Riz cantonais',
 'Riz saute style cantonais',
 '1. Faire cuire le riz et le laisser refroidir. 
  2. Battre les oeufs dans un bol. 
  3. Faire cuire les oeufs dans une poele et les reserver. 
  4. Couper le jambon en petits morceaux. 
  5. Faire chauffer un peu d huile dans une poele ou un wok. 
  6. Ajouter le riz et le faire revenir. 7. Ajouter le jambon et les oeufs. 
  8. Ajouter la sauce soja. 9. Bien melanger et servir chaud.',
 'images/riz_cantonais.jpeg'),

('Paris Brest',
 'Dessert classique a base de pate a choux et creme pralinee',
 '1. Prechauffer le four a 180 degres. 
  2. Dans une casserole, faire chauffer l eau, le beurre et une pincee de sel. 
  3. Ajouter la farine d un coup et melanger jusqu a obtenir une pate. 
  4. Hors du feu, ajouter les oeufs un par un en melangeant. 
  5. Mettre la pate dans une poche a douille et former une couronne sur une plaque. 
  6. Cuire au four pendant 30 a 35 minutes. 
  7. Pendant ce temps, preparer la creme pralinee en melangeant beurre, sucre et pralin. 
  8. Laisser refroidir la couronne puis la couper en deux. 
  9. Garnir avec la creme.
  10. Refermer et saupoudrer de sucre glace. 11. Servir frais.',
 'images/paris_brest.jpeg'),

('Rechta',
 'Plat Algerien traditionnel a base de pate de rechta, viande et legumes',
 '1. Eplucher et couper les legumes en morceaux.
  2. Faire revenir l oignon avec un peu d huile dans une marmite. 
  3. Ajouter la viande et laisser dorer quelques minutes. 
  4. Ajouter les pois chiches, les carottes, les courgettes et les navets. 
  5. Ajouter de l eau, du sel et les epices puis laisser cuire a feu moyen. 
  6. Pendant ce temps, preparer la pate de rechta a la vapeur ou selon les instructions. 
  7. Quand la viande et les legumes sont cuits, dresser la rechta dans un plat. 
  8. Ajouter les legumes et la sauce par-dessus. 
  9. Servir chaud.',
 'images/rechta.jpeg'),

('Chicken Tikka Masala',
 'Plat Indien a base de poulet marine et sauce tomate cremeuse',
 '1. Couper le poulet en morceaux. 
  2. Dans un saladier, melanger le poulet avec du yaourt, de l ail, du sel et des epices. 
  3. Laisser mariner au moins 30 minutes. 
  4. Faire revenir l oignon dans une poele avec un peu d huile. 
  5. Ajouter l ail puis la tomate et laisser cuire quelques minutes. 
  6. Ajouter les morceaux de poulet et bien melanger. 
  7. Ajouter la creme fraiche et laisser mijoter a feu doux pendant 20 minutes. 
  8. Verifier la cuisson du poulet. 
  9. Servir chaud avec du riz.',
 'images/chicken_tikka_masala.jpeg');

INSERT INTO ingredient (nom) VALUES
('spaghetti'),
('viande hachee'),
('sauce tomate'),
('salade'),
('poulet'),
('parmesan'),
('oeufs'),
('fromage'),
('farine'),
('lait'),
('sucre'),
('banane'),
('yaourt'),
('beurre'),
('chocolat'),
('tomate'),
('riz'),
('oignon'),
('ail'),
('creme_fraiche'),
('pates'),
('avocat'),
('fraise'),
('thon'),
('pommes'),
('pommes_de_terre'),
('mozzarella'),
('pate_a_pizza'),
('sauce_soja'),
('carotte'),
('courgette'),
('pois_chiches'),
('semoule'),
('eau'),
('sel'),
('pralin'),
('sucre_glace'),
('pate_de_rechta'),
('navet'),
('huile'),
('jambon'),
('concombre'),
('raisin_sec');

INSERT INTO tag (nom) VALUES
('sucre'),
('sale'),
('healthy'),
('petit_dejeuner'),
('cuisine_du_monde'),
('plat_principal');

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Spaghetti bolognaise' AND t.nom = 'plat_principal';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Salade César' AND t.nom = 'healthy';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Omelette fromage' AND t.nom = 'sale';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Pancakes' AND t.nom = 'sucre';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Pancakes' AND t.nom = 'petit_dejeuner';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Smoothie banane' AND t.nom = 'sucre';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Tarte aux pommes' AND t.nom = 'sucre';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Crepes maison' AND t.nom = 'sucre';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Mousse au chocolat' AND t.nom = 'sucre';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Pizza margherita' AND t.nom = 'sale';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Gratin dauphinois' AND t.nom = 'sale';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Couscous' AND t.nom = 'plat_principal';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Couscous' AND t.nom = 'cuisine_du_monde';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Wrap thon salade' AND t.nom = 'healthy';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Riz cantonais' AND t.nom = 'sale';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Paris Brest' AND t.nom = 'sucre';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Rechta' AND t.nom = 'cuisine_du_monde';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Chicken Tikka Masala' AND t.nom = 'cuisine_du_monde';

INSERT INTO recette_tag (id_recette, id_tag)
SELECT r.id, t.id
FROM recette r, tag t
WHERE r.titre = 'Chicken Tikka Masala' AND t.nom = 'plat_principal';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'spaghetti';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'pates';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '150 g'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'viande hachee';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 ml'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'sauce tomate';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '30 g'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'parmesan';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'oignon';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 gousse'
FROM recette r, ingredient i
WHERE r.titre = 'Spaghetti bolognaise' AND i.nom = 'ail';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 salade'
FROM recette r, ingredient i
WHERE r.titre = 'Salade César' AND i.nom = 'salade';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '150 g'
FROM recette r, ingredient i
WHERE r.titre = 'Salade César' AND i.nom = 'poulet';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '50 g'
FROM recette r, ingredient i
WHERE r.titre = 'Salade César' AND i.nom = 'parmesan';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Salade César' AND i.nom = 'tomate';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '3'
FROM recette r, ingredient i
WHERE r.titre = 'Omelette fromage' AND i.nom = 'oeufs';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '50 g'
FROM recette r, ingredient i
WHERE r.titre = 'Omelette fromage' AND i.nom = 'fromage';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '10 g'
FROM recette r, ingredient i
WHERE r.titre = 'Omelette fromage' AND i.nom = 'beurre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Pancakes' AND i.nom = 'oeufs';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Pancakes' AND i.nom = 'farine';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '250 ml'
FROM recette r, ingredient i
WHERE r.titre = 'Pancakes' AND i.nom = 'lait';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '30 g'
FROM recette r, ingredient i
WHERE r.titre = 'Pancakes' AND i.nom = 'sucre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Smoothie banane' AND i.nom = 'banane';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 pot'
FROM recette r, ingredient i
WHERE r.titre = 'Smoothie banane' AND i.nom = 'yaourt';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 c. a soupe'
FROM recette r, ingredient i
WHERE r.titre = 'Smoothie banane' AND i.nom = 'sucre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '3'
FROM recette r, ingredient i
WHERE r.titre = 'Tarte aux pommes' AND i.nom = 'pommes';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Tarte aux pommes' AND i.nom = 'farine';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '100 g'
FROM recette r, ingredient i
WHERE r.titre = 'Tarte aux pommes' AND i.nom = 'sucre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '50 g'
FROM recette r, ingredient i
WHERE r.titre = 'Tarte aux pommes' AND i.nom = 'beurre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '250 g'
FROM recette r, ingredient i
WHERE r.titre = 'Crepes maison' AND i.nom = 'farine';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '3'
FROM recette r, ingredient i
WHERE r.titre = 'Crepes maison' AND i.nom = 'oeufs';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '500 ml'
FROM recette r, ingredient i
WHERE r.titre = 'Crepes maison' AND i.nom = 'lait';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '30 g'
FROM recette r, ingredient i
WHERE r.titre = 'Crepes maison' AND i.nom = 'sucre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Mousse au chocolat' AND i.nom = 'chocolat';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '4'
FROM recette r, ingredient i
WHERE r.titre = 'Mousse au chocolat' AND i.nom = 'oeufs';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '50 g'
FROM recette r, ingredient i
WHERE r.titre = 'Mousse au chocolat' AND i.nom = 'sucre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Pizza margherita' AND i.nom = 'pate_a_pizza';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Pizza margherita' AND i.nom = 'tomate';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '150 g'
FROM recette r, ingredient i
WHERE r.titre = 'Pizza margherita' AND i.nom = 'mozzarella';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 kg'
FROM recette r, ingredient i
WHERE r.titre = 'Gratin dauphinois' AND i.nom = 'pommes_de_terre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '30 cl'
FROM recette r, ingredient i
WHERE r.titre = 'Gratin dauphinois' AND i.nom = 'creme_fraiche';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 gousse'
FROM recette r, ingredient i
WHERE r.titre = 'Gratin dauphinois' AND i.nom = 'ail';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '300 g'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'semoule';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'carotte';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'courgette';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '150 g'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'pois_chiches';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'tomate';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'pommes_de_terre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Couscous' AND i.nom = 'oignon';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '120 g'
FROM recette r, ingredient i
WHERE r.titre = 'Wrap thon salade' AND i.nom = 'thon';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Wrap thon salade' AND i.nom = 'salade';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1/2'
FROM recette r, ingredient i
WHERE r.titre = 'Wrap thon salade' AND i.nom = 'tomate';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Riz cantonais' AND i.nom = 'riz';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Riz cantonais' AND i.nom = 'oeufs';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '100 g'
FROM recette r, ingredient i
WHERE r.titre = 'Riz cantonais' AND i.nom = 'jambon';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 c. a soupe'
FROM recette r, ingredient i
WHERE r.titre = 'Riz cantonais' AND i.nom = 'sauce_soja';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '100 g'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'farine';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '4'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'oeufs';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '100 g'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'beurre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '25 cl'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'eau';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 pincee'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'sel';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '150 g'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'sucre';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '100 g'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'pralin';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '20 g'
FROM recette r, ingredient i
WHERE r.titre = 'Paris Brest' AND i.nom = 'sucre_glace';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '300 g'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'pate_de_rechta';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'carotte';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'courgette';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'navet';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'oignon';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '150 g'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'pois_chiches';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2 c. a soupe'
FROM recette r, ingredient i
WHERE r.titre = 'Rechta' AND i.nom = 'huile';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '300 g'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'poulet';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1 pot'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'yaourt';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'tomate';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '1'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'oignon';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2 gousses'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'ail';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '20 cl'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'creme_fraiche';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '200 g'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'riz';

INSERT INTO recette_ingredient (id_recette, id_ingredient, quantite)
SELECT r.id, i.id, '2 c. a soupe'
FROM recette r, ingredient i
WHERE r.titre = 'Chicken Tikka Masala' AND i.nom = 'huile';