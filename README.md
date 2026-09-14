# Gestion des congés

Application web de gestion des congés permettant aux employés de soumettre leurs demandes et aux responsables de les traiter selon une organisation hiérarchique.

L'application centralise la gestion des employés, des structures, des rôles, des demandes de congés et de leur validation. Elle intègre également la gestion des soldes annuels, des jours fériés, des délégations de rôle, des notifications et la génération de documents PDF.

## Fonctionnalités

### Authentification

- Inscription et connexion des utilisateurs
- Réinitialisation du mot de passe
- Vérification de l'adresse e-mail
- Gestion du profil utilisateur
- Modification du mot de passe
- Confirmation du mot de passe pour certaines opérations

### Gestion des employés

- Création et gestion des employés
- Association d'un employé à un utilisateur
- Attribution d'un rôle
- Association à une structure organisationnelle
- Consultation des informations liées aux congés

### Gestion des structures

L'application permet de représenter une organisation composée de plusieurs niveaux hiérarchiques.

Une structure peut avoir une structure parente et plusieurs structures enfants.

Exemple :

```text
Direction
├── Département
│   ├── Service A
│   └── Service B
└── Département 2
    └── Service C
```

Cette hiérarchie est utilisée lors du traitement des demandes afin de déterminer les responsables concernés.

### Gestion des demandes de congés

Un employé peut :

- créer une demande de congé ;
- sélectionner le type de congé ;
- définir les dates de début et de fin ;
- renseigner les informations nécessaires à la demande ;
- sélectionner un remplaçant ;
- joindre un justificatif lorsque cela est nécessaire ;
- consulter l'état de sa demande ;
- consulter l'historique de traitement ;
- télécharger sa demande au format PDF.

### Workflow de validation

Les demandes suivent un processus de validation dépendant de l'organisation et du rôle des responsables.

Le workflow peut notamment suivre le chemin :

```text
Employé
   │
   ▼
Chef de service
   │
   ▼
Chef de département
   │
   ▼
Direction
   │
   ▼
Ressources humaines
```

Chaque étape peut accepter ou refuser la demande.

Le système détermine automatiquement l'étape suivante en fonction du niveau actuel de validation.

### Gestion des rôles

Le système prend en compte différents niveaux de responsabilité, notamment :

- Administrateur
- Employé
- Responsable de service
- Responsable de département
- Directeur
- Ressources humaines

Les demandes accessibles à un responsable sont filtrées en fonction de son rôle et de sa structure organisationnelle.

### Délégation de rôle

Un responsable peut déléguer temporairement son rôle à un autre employé.

Une délégation contient notamment :

- l'employé concerné ;
- le responsable qui délègue ;
- le rôle délégué ;
- une date de début ;
- une date de fin.

Cela permet de maintenir le processus de validation lorsqu'un responsable est absent.

### Gestion des droits de congés

L'application gère les droits de congés par année.

Les droits comprennent notamment :

- le nombre de jours attribués ;
- les jours pris ;
- les jours restants ;
- l'année concernée.

La création des droits annuels peut être automatisée grâce à une commande Artisan.

```bash
php artisan leaves:create-annual-rights
```

Cette commande est également prévue pour être exécutée automatiquement chaque année via le scheduler Laravel.

### Gestion des jours fériés

Les jours fériés sont pris en compte dans la gestion des périodes de congés afin d'adapter le calcul des jours concernés.

### Notifications

Les utilisateurs reçoivent des notifications internes lors des différentes étapes du traitement d'une demande.

Par exemple :

```text
Nouvelle demande à traiter
        ↓
Décision du responsable
        ↓
Notification de l'employé
        ↓
Passage à l'étape suivante
```

### Calendrier

L'application intègre un calendrier permettant de visualiser les informations liées aux congés.

Le calendrier est réalisé avec **FullCalendar**.

### Génération de PDF

Les demandes peuvent être générées au format PDF à partir d'une vue dédiée.

La génération est réalisée côté serveur avec **DomPDF**.

### QR Code et vérification

L'application intègre également la génération de QR codes et une interface de vérification d'une demande à partir de son identifiant.

---

# Architecture

Le projet suit l'architecture MVC de Laravel.

```text
app/
├── Console/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Requests/
│   └── View/
├── Models/
├── Notifications/
├── Observers/
├── Providers/
├── Services/
└── View/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
└── auth.php

tests/
├── Feature/
└── Unit/
```

## Organisation du code

### Models

Les modèles Eloquent représentent les principales entités métier :

- `User`
- `Employe`
- `Role`
- `Structure`
- `Demande`
- `Type`
- `StatutConge`
- `Etape`
- `DroitConge`
- `Solde`
- `Exercice`
- `JourFerie`
- `Notification`
- `DelegationRole`

Les relations entre ces modèles permettent de représenter les liens entre employés, structures, rôles et demandes de congés.

### Services

La logique métier qui nécessite un traitement spécifique est regroupée dans des services dédiés.

Par exemple, la gestion de la hiérarchie des structures est réalisée par un service permettant de récupérer récursivement les structures descendantes.

### Observers

Des observers sont utilisés pour exécuter automatiquement certaines opérations liées au cycle de vie des modèles.

### Form Requests

Les validations des données envoyées par les utilisateurs sont séparées dans des classes `FormRequest`, afin de garder les contrôleurs plus lisibles.

### Middleware

Les middleware permettent notamment de contrôler l'accès aux différentes parties de l'application en fonction du contexte de l'utilisateur.

---

# Technologies utilisées

## Backend

- **PHP 8.1+**
- **Laravel 10**
- **Eloquent ORM**
- **Laravel Breeze**
- **Laravel Sanctum**
- **Guzzle**

## Frontend

- **Blade**
- **Tailwind CSS**
- **Alpine.js**
- **JavaScript**
- **Axios**

## Outils et bibliothèques

- **Vite**
- **FullCalendar**
- **DomPDF**
- **Simple QR Code**
- **Laravel Tinker**
- **PHPUnit**
- **Laravel Pint**

---

# Installation

## Prérequis

Avant d'installer le projet, vérifier que les éléments suivants sont disponibles :

- PHP 8.1 ou supérieur
- Composer
- Node.js et npm
- Une base de données compatible avec Laravel
- Git

## Cloner le projet

```bash
git clone https://github.com/aboudAG/gestionconge.git
cd gestionconge
```

## Installer les dépendances PHP

```bash
composer install
```

## Installer les dépendances JavaScript

```bash
npm install
```

## Configuration de l'environnement

Copier le fichier `.env.example` :

```bash
cp .env.example .env
```

Puis générer la clé de l'application :

```bash
php artisan key:generate
```

Configurer ensuite les informations de connexion à la base de données dans `.env`.

Exemple :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestionconge
DB_USERNAME=root
DB_PASSWORD=
```

## Base de données

Lancer les migrations disponibles :

```bash
php artisan migrate
```

Si des données de démonstration sont disponibles via les seeders :

```bash
php artisan db:seed
```

## Compiler les assets

Pour le développement :

```bash
npm run dev
```

Pour une version de production :

```bash
npm run build
```

## Lancer l'application

Dans un autre terminal :

```bash
php artisan serve
```

L'application sera alors accessible à l'adresse :

```text
http://127.0.0.1:8000
```

---

# Tests

Les tests sont exécutés avec PHPUnit :

```bash
php artisan test
```

Les tests présents couvrent notamment différentes fonctionnalités liées à :

- l'authentification ;
- l'inscription ;
- la vérification de l'e-mail ;
- la réinitialisation du mot de passe ;
- la confirmation du mot de passe ;
- la modification du mot de passe ;
- le profil utilisateur.

---

# Commandes utiles

Lancer le serveur Laravel :

```bash
php artisan serve
```

Compiler les assets en développement :

```bash
npm run dev
```

Compiler les assets pour la production :

```bash
npm run build
```

Exécuter les tests :

```bash
php artisan test
```

Créer les droits annuels :

```bash
php artisan leaves:create-annual-rights
```

Accéder à Tinker :

```bash
php artisan tinker
```

---

# Flux général d'une demande

```text
┌──────────────┐
│   Employé    │
└──────┬───────┘
       │
       │ Création de la demande
       ▼
┌──────────────────────┐
│ Demande de congé     │
└──────────┬───────────┘
           │
           ▼
┌──────────────────────┐
│ Première validation   │
└──────────┬───────────┘
           │
       Acceptée ?
        /       \
      Non        Oui
      │           │
      ▼           ▼
    Refus      Étape suivante
                  │
                  ▼
           Validation suivante
                  │
                  ▼
                  ...
                  │
                  ▼
          ┌──────────────┐
          │     RH       │
          └──────┬───────┘
                 │
                 ▼
        Validation finale
```

---

# Gestion de la hiérarchie

Le système utilise une relation parent/enfant entre les structures.

```text
Direction
│
├── Département 1
│   ├── Service 1
│   └── Service 2
│
└── Département 2
    ├── Service 3
    └── Service 4
```

Lorsqu'un responsable consulte les demandes, l'application peut déterminer les structures qui se trouvent sous son périmètre de responsabilité.

Cette logique permet d'éviter de donner à chaque responsable accès à l'ensemble des demandes de l'application.

---

# Objectifs techniques du projet

Le projet a été développé autour de plusieurs objectifs :

- centraliser la gestion des congés ;
- remplacer un traitement manuel des demandes ;
- prendre en compte une organisation hiérarchique ;
- automatiser le processus de validation ;
- gérer les droits annuels ;
- assurer le suivi des décisions ;
- permettre la délégation temporaire des responsabilités ;
- fournir une interface web accessible aux différents profils d'utilisateurs.

---

# Statut du projet

Projet fonctionnel de gestion des congés développé avec Laravel.

Le projet constitue une application web complète mettant en œuvre un ensemble de fonctionnalités backend et frontend autour d'un processus métier réel.

---
