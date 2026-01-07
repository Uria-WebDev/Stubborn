Documentation – Boutique en ligne Stubborn

1. Présentation du projet

- La marque de sweat-shirts Stubborn souhaite disposer d’une boutique en ligne afin de présenter et vendre ses collections. Ce projet consiste à développer une application web complète avec Symfony, intégrant une base de données MySQL, un système d’authentification, un panier, un paiement en ligne via Stripe et un back-office administrateur.



2. Technologies utilisées

- Symfony (Framework PHP)
- PHP 8.3.6
- MySQL (Base de données relationnelle)
- Doctrine ORM
- Twig (moteur de templates)
- Symfony Security (authentification et autorisations)
- Stripe API (paiement en ligne – mode test)
- PHPUnit (tests unitaires)
- Git & GitHub (versionnement du code)



3. Gestion du code source

- Le projet est versionné à l’aide de Git et hébergé sur GitHub.
- Initialisation du dépôt Git
- Commits réguliers
- Respect d’une arborescence Symfony standard



4. Base de données

- Une base MySQL est utilisée pour stocker les données de l’application.

4.1 Modélisation (principales entités)

- User : utilisateurs (clients et administrateurs)
- Product : sweat-shirts

- Doctrine ORM est utilisé pour la gestion des entités et des relations.



5. Authentification et sécurité

5.1 Rôles

- ROLE_USER : client connecté
- ROLE_ADMIN : administrateur

5.2 Fonctionnalités

- Inscription utilisateur
- Connexion / déconnexion
- Confirmation d’inscription par email
- Connexion automatique après confirmation
- Protection des routes via security.yaml



6. Pages et fonctionnalités
   
6.1 Page d’accueil (/)

- Présentation de la société Stubborn
- Mise en avant de sweat-shirts

Utilisateur non connecté :
- Navigation : Accueil, S’inscrire, Se connecter
- Boutons « Voir » masqués

Utilisateur connecté :
- Navigation : Accueil, Boutique, Panier, Se déconnecter

6.2 Connexion (/login)

- Formulaire de connexion
- Redirection vers la page d’accueil après authentification réussie

6.3 Inscription (/register)

- Formulaire d’inscription
- Envoi d’un email de confirmation
- Lien de confirmation unique
- Connexion automatique après validation de l’email

6.4 Catalogue produits (/products)

- Liste de tous les sweat-shirts
- Filtrage par fourchette de prix :
                                   10 € – 29 €
                                   29 € – 35 €
                                   35 € – 50 €

6.5 Fiche produit (/product/{id})

- Détails d’un sweat-shirt
- Ajout du produit au panier

6.6 Panier (/cart)

- Affichage des articles
- Suppression d’un article
- Calcul du total
- Validation de la commande
- Accès au paiement



7. Paiement avec Stripe

7.1 Intégration de Stripe

- Création d’un compte Stripe (mode test)
- Utilisation de l’API Stripe

7.2 Service de paiement

- Créer une session de paiement
- Simuler un règlement en environnement de développement



8. Back-office administrateur (/admin)

- Accessible uniquement aux utilisateurs ayant le rôle ROLE_ADMIN.
- Ajouter un sweat-shirt
- Modifier un sweat-shirt
- Supprimer un sweat-shirt



9. Tests unitaires

- Des tests unitaires sont réalisés avec PHPUnit pour :
                                                       Les fonctionnalités du panier
                                                       Le calcul du total
                                                       Le processus de règlement d’une commande
