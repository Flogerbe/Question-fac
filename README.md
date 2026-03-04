# 🏆 FAC Andrézieux — Quiz Assemblée Générale

Application web de quiz interactive développée pour l'Assemblée Générale du **FAC Andrézieux** (Forez Athletic Club). Les adhérents peuvent tester leurs connaissances sur l'athlétisme et le club, s'affronter au classement et voter pour le futur maillot du club.

---

## Fonctionnalités

### Côté public
- **Quiz interactif** — 20 questions chronométrées (30 secondes par question)
- **Système de points** — 500 pts de base + jusqu'à 500 pts de bonus vitesse
- **3 jokers** — 50/50, Vote du public, Question au coach
- **Retour visuel** — affichage en vert/rouge de la bonne/mauvaise réponse après chaque réponse
- **Classement général** — podium visuel (mode points) ou liste des participants (mode tirage)
- **Vote maillot** — vote pour le futur maillot du club
- **Anti-triche** — une participation par personne (IP + browser token)

### Back-office admin
- Gestion des questions et réponses (CRUD complet)
- Classement avec suppression de sessions
- **Tirage au sort** — 3 catégories configurables : Esprit Club, 100% Champion, Bonus
- Gestion des votes maillot
- Liste des joueurs avec historique
- **Paramètres dynamiques** : couleurs, logo, textes, mode de participation, mode classement
- Mode de participation configurable : une fois au total / une fois par jour / illimité
- Mode classement configurable : par points ou tirage au sort

---

## Stack technique

| Composant | Version |
|-----------|---------|
| PHP | 8.3 |
| Laravel | 12 |
| Base de données | MySQL |
| Auth | Laravel Breeze (Blade) |
| Build | Vite |
| CSS | Vanilla CSS (variables dynamiques) |

---

## Installation

### Prérequis
- PHP ≥ 8.2
- Composer
- MySQL
- Node.js + npm

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/Flogerbe/Question-fac.git
cd Question-fac

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JS et builder
npm install && npm run build

# 4. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 5. Configurer la BDD dans .env
# DB_DATABASE=fac_quiz
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Créer les tables et seeder les questions
php artisan migrate
php artisan db:seed
```

> Pour créer un compte admin directement en base :
> ```bash
> php artisan tinker --execute="App\Models\User::create(['name'=>'Admin','email'=>'admin@fac-andrezieux.fr','password'=>bcrypt('votre-mot-de-passe'),'email_verified_at'=>now()]);"
> ```

---

## Configuration

Tous les paramètres sont modifiables depuis le back-office **`/admin/parametres`** :

| Paramètre | Description |
|-----------|-------------|
| Couleurs | Couleur principale, foncée, orange |
| Logo | Import JPG/PNG/GIF |
| Titre & sous-titre | Texte affiché sur la page d'accueil |
| Mode participation | `once` / `par_jour` / `illimite` |
| Nombre de participations | Ex : 1 = une seule fois |
| Mode classement | `points` (podium classique) / `tirage` (liste participants) |
| Nb gagnants Esprit Club | Nombre de gagnants tirés pour ce lot |
| Nb gagnants 100% Champion | Nombre de gagnants tirés (sans faute uniquement) |
| Nb gagnants Bonus | Nombre de gagnants tirés pour le lot bonus |

---

## Accès admin

| URL | Description |
|-----|-------------|
| `/admin` | Dashboard |
| `/admin/questions` | Gestion des questions |
| `/admin/classement` | Classement & suppression |
| `/admin/tirage` | Tirage au sort (3 catégories) |
| `/admin/maillot` | Vote maillot |
| `/admin/joueurs` | Liste des joueurs |
| `/admin/parametres` | Paramètres du site |

---

## Tirage au sort

Le système de tirage au sort permet de désigner des gagnants parmi les participants de façon aléatoire en fin d'événement.

### 3 catégories
| Catégorie | Éligibles |
|-----------|-----------|
| 🎟️ **Esprit Club** | Tous les participants ayant terminé le quiz |
| 🏅 **100% Champion** | Uniquement les participants ayant répondu correctement à toutes les questions |
| 🎁 **Bonus** | Tous les participants (2e chance) |

### Utilisation
1. Dans **Paramètres**, passer le mode classement en `Tirage au sort` et configurer le nombre de gagnants par catégorie
2. Dans **Tirage au sort**, cliquer sur 🎲 "Lancer le tirage" pour chaque catégorie
3. Les résultats sont sauvegardés et peuvent être relancés à tout moment

---

## Structure du projet

```
app/
├── Http/Controllers/
│   ├── QuizController.php        # Logique du quiz (jouer, jokers, vérifier)
│   ├── HomeController.php        # Page d'accueil + classement
│   ├── JerseyController.php      # Vote maillot
│   └── Admin/                    # Back-office
│       ├── DashboardController.php
│       ├── QuestionController.php
│       ├── LeaderboardController.php
│       ├── TirageController.php  # Tirage au sort
│       ├── JerseyController.php
│       ├── PlayersController.php
│       └── SettingsController.php
├── Models/
│   ├── Question.php / Answer.php
│   ├── Player.php                # Anti-triche (IP hash + browser token)
│   ├── GameSession.php           # Session de jeu
│   ├── GameAnswer.php            # Réponses enregistrées
│   ├── TirageResult.php          # Résultats des tirages au sort
│   ├── JerseyOption.php / JerseyVote.php
│   └── SiteSetting.php           # Paramètres dynamiques

resources/views/
├── layouts/fac.blade.php         # Layout principal (couleurs dynamiques)
├── home.blade.php                # Page d'accueil
├── classement.blade.php          # Classement (mode points ou tirage)
├── regles.blade.php              # Règles du jeu
├── quiz/                         # Formulaire, question, résultat
└── admin/                        # Back-office
    ├── tirage.blade.php          # Interface tirage au sort
    └── tirage-winners.blade.php  # Partiel affichage gagnants
```

---

## Système anti-triche

Chaque joueur est identifié par :
1. **IP hashée** (SHA-256 + app.key) — bloque les tentatives depuis le même réseau
2. **Browser token** (UUID localStorage, hashé) — bloque les tentatives depuis le même navigateur

Le mode de participation est configurable dans les paramètres admin.

---

## Licence

Projet interne — FAC Andrézieux, Andrézieux-Bouthéon, Loire (42).
*"Une autre idée de l'athlé"*
