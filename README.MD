# Oumaima_aitsaid_manager

**Gestionnaire avancé**

**Author du Brief:** Iliass RAIHANI.

**Author:** Oumaima Ait said.

## Links

- **GitHub Repository :** [View on GitHub](https://github.com/Youcode-Classe-E-2024-2025/oumaima_aitsaid_manager.git)
- **UML Link :** [View UML](https://lucid.app/lucidchart/dca982c1-8dec-459a-bb1b-d065ca174c2b/edit?viewport_loc=-1933%2C-319%2C4215%2C1748%2C0_0&invitationId=inv_bb5a57fe-3a0c-4bb4-85eb-67b7e92e39e2)
- **ERD Link :** [View ERD](https://dbdiagram.io/d/67687e10fc29fb2b3b1bf621)

### créé : 15/12/24

Développer une application sécurisée avec une gestion utilisateur et administrateur.
Mettre en œuvre des pratiques modernes de sécurité web.
Utiliser JavaScript pour améliorer l’interactivité et la convivialité de l’application.


# Configuration et Exécution du Projet

### Prérequis
* **Node.js** et **npm** installés (téléchargez [Node.js](https://nodejs.org/)).
* **Laragon** installé (téléchargez [Laragon](https://laragon.org/download/)).

### Étapes d’installation

1. **Cloner le projet** :
   - Ouvrir un terminal et exécuter :  
     `git clone https://github.com/Youcode-Classe-E-2024-2025/oumaima_aitsaid_manager.git`

2. **Placer le projet dans le dossier Laragon** :
   - Cliquez sur le bouton **Root** dans Laragon pour ouvrir le dossier `www` (par défaut, `C:\laragon\www`).
   - Le chemin de votre projet devrait être `C:\laragon\www\GESTION_MANAGER`.

3. **Configurer la base de données** :
   - Faites un clic droit sur **Laragon**, puis allez dans **Tools** > **Quick Add** et téléchargez **phpMyAdmin** et **MySQL**.
   - Ouvrir **phpMyAdmin** via Laragon :
     - Dans Laragon, cliquez sur le bouton **Database** pour accéder à phpMyAdmin (username = `root` et mode de passe est vide).
     - La base de données est automatiquement créez ou vous pouvez Créez une base de données `gestion_restaurants` et importez le fichier `script.sql` (disponible dans le dossier `/sql/insert_data.sql`).


4. **Installer les dépendances Node.js** :
   - Ouvrez un terminal dans le dossier du projet cloné.
   - Exécutez :  `npm install` or `npm i`

5. **Configurer Laragon pour le serveur local** :
   - Lancez **Laragon** et démarrez les services **Apache** et **MySQL**,en Clickant sur **Start All**.


6. **Exécuter le projet** :
   - Une fois les services lancés dans Laragon, cliquez sur le bouton **Web** pour accéder à `http://localhost/GESTION_MANAGER` dans votre navigateur.



## **Contexte du projet:**

1. Utilisateurs (User)

Création de compte :

- Formulaire sécurisé pour inscrire un nouvel utilisateur.
- Validation des champs via Regex côté client et serveur.

Authentification :

- Connexion sécurisée.
- Redirection vers la page utilisateur après succès.

​

2. Administrateurs (Admin)

Authentification : Connexion sécurisée avec redirection vers un tableau de bord.
Validation des comptes utilisateurs : Liste des nouvelles inscriptions avec options pour approuver ou rejeter.

Tableau de bord avec statistiques : Agrégation des données, par exemple : nombre de nouveaux comptes par jour.

Gestion des données utilisateur :

- CRUD : Création, consultation, modification et suppression des comptes et données associées.
- Archivage : Supprimer un utilisateur en le marquant comme "archivé" pour conserver ses données en base (soft delete).

​
3. Sécurité des données

**Hachage des mots de passe :** Utilisation de bibliothèques comme bcrypt ou argon2 pour sécuriser les mots de passe.

Protection contre XSS et CSRF :

- Validation des entrées utilisateur et échappement des scripts.
- Utilisation de jetons CSRF pour protéger les formulaires.

Requêtes SQL préparées : Prévention des injections SQL grâce à des requêtes sécurisées.


4. Fonctionnalités JavaScript

Validation avec Regex : Vérification dynamique des formulaires pour s’assurer du format des e-mails, mots de passe, etc. Formulaires dynamiques :

- Ajout ou suppression de champs en temps réel.
- Par exemple : possibilité d’ajouter plusieurs auteurs pour un même livre.

Affichage interactif :

- Utilisation de modals pour afficher des actions ou des détails sans recharger la page.
- Intégration de SweetAlerts pour des alertes conviviales et esthétiques.

​

5. Fonctionnalités Bonus

**Impression :** Génération de fichiers PDF pour les rapports ou états des livres.

Envoi d’e-mails :

- Réinitialisation de mot de passe.
- Notifications pour la confirmation de compte ou autres actions.

**Identifiant de document unique :** Génération automatique d’un identifiant pour chaque livre ou utilisateur.



## **Modalités pédagogiques**

    Travail: individuel
    Durée de travail: 5 jours
    Date de lancement du brief: 16/12/2024 à 09:00 am
    Date limite de soumission: 20/12/2024 avant 12:00 am



## **Modalités d'évaluation**

    - 5 min démonstration 
    - 5 min code Review \ Questions culture Web
    - 10 min mise en situation

## **Livrables**

    Modélisation :
        - Diagramme ERD basé sur le schéma fourni.
        - Diagramme UML de cas d’utilisation.

    Scripts SQL :
        - Scripts pour créer et manipuler la base de données.

    Code PHP :
        - Code source dans un repository git avec le nom : prénom_nom-manager.

    Documentation README :
        - Instructions pour configurer l’environnement et exécuter le projet.

## **Critères de performance**

    - L'utilisation d'au moins 5 entités.
    - Fonctionnalités implémentées.
    - Sécurité et robustesse de l'application.
    - Qualité du code (modularité, documentation).
    - Esthétique et ergonomie de l'interface utilisateur.
