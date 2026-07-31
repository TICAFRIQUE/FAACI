# FAACI — Plateforme Numérique

> **Fondation AIESEC Alumni CI** — Hub réseau Alumni et plateforme de financement communautaire interne.

---

## Identité

| Champ | Valeur |
|---|---|
| Nom du projet | Plateforme FAACI |
| Type | Application web full-stack |
| Public | Membres Alumni AIESEC Côte d'Ivoire + administrateurs |
| Langues | Français (par défaut), Anglais (V2) |
| Couleurs | Navy `#0D1F3C` · Steel `#4A7FA5` · Silver `#9BAAB8` · Blanc |
| Polices | Playfair Display (titres), DM Sans (corps) |

---

## Stack technique

- **Backend** : Laravel 13 (PHP 8.3+)
- **Frontend** : Blade + Alpine.js + **Bootstrap 5.3** (front public + back-office)
- **Authentification** : **Auth custom** (login, register, reset password faits maison, sans Breeze/Jetstream)
- **Base de données** : MySQL 8
- **Hébergement cible** : cPanel / shared hosting compatible

### Packages obligatoires
- **Spatie MediaLibrary** → gestion des images et fichiers
- **Spatie Laravel Permission** → rôles et permissions
- **Yajra DataTables** → pagination server-side sur toutes les listes
- **Laravel Cache** (file driver) → mise en cache des données lourdes
- **Spatie Activity Log** → journal d'activité

### Conventions de code
- **Tables en français** : `utilisateurs`, `projets`, `contributions`, `entreprises`, `opportunites`, `offres_emploi`, `candidatures`, `evenements`, `inscriptions_evenements`, `articles`, `notifications`, `logs_activite`, `medias`, `slides`, `membres_equipe`, `valeurs`, `albums_galerie`, `images_galerie`, `visites`, `parametres_site`, `contenus_sections`
- **Colonnes en français** : `nom`, `prenom`, `titre`, `description`, `montant_promis`, `montant_paye`, `statut`, `date_creation`, etc.
- **Routes Laravel en français** : `/projets`, `/annuaire`, `/entreprises`, `/opportunites`, `/emplois`, `/evenements`, `/admin/slides`, `/admin/equipe`, etc.
- **Cache invalidation** : via Observers Eloquent sur chaque modification de contenu public
- **Déploiement** : `composer install` sur serveur, jamais `composer update`

---

## Responsive

- **Mobile first** (priorité absolue — public africain majoritairement mobile)
- Parfaitement adapté tablette
- Desktop complet

---

## Règle de visibilité

- **Site vitrine** : 100 % public, purement institutionnel
- **Projets, financements, annuaire, entreprises, opportunités, offres d'emploi** : 100 % privés, accès aux **membres authentifiés actifs uniquement**
- Un visiteur du site vitrine ne voit jamais de projet, ni de promesse, ni de contributeur

---

## Rôles & permissions (Spatie)

Seulement **3 rôles** liés aux permissions techniques :

| Rôle | Description |
|---|---|
| `membre` | Tout utilisateur Alumni du système |
| `admin` | Administration totale sauf gestion des admins |
| `super_admin` | Configuration système + gestion des admins |

Le visiteur public n'est pas un rôle — c'est simplement un utilisateur non authentifié (`auth()->guest()`).

---

## Statuts des membres

Le **statut** du membre détermine son accès effectif à la plateforme, indépendamment de son rôle.

| Statut | Description | Accès effectif |
|---|---|---|
| `en_attente` | Demande d'adhésion soumise, en attente de validation admin | Voir uniquement le statut de sa demande |
| `actif` | Membre validé et en règle | Espace membre complet |
| `suspendu` | Temporairement suspendu (cotisation, comportement…) | Bloqué, réactivable |
| `inactif` | A quitté volontairement ou n'a pas renouvelé | Bloqué, historique conservé |
| `rejete` | Demande d'adhésion refusée par l'admin | Bloqué, motif visible, peut re-soumettre plus tard |

### Champs liés au statut sur la table `utilisateurs`
- `statut` (enum)
- `motif_rejet` (nullable, texte)
- `motif_suspension` (nullable, texte)
- `date_validation` (nullable)
- `valide_par` (nullable, fk admin)
- `date_suspension` (nullable)
- `date_inactivation` (nullable)

### Logique d'accès (middleware combiné)

```
auth()->check() 
    && user.hasRole('membre') 
    && user.statut === 'actif' 
    → accès espace membre
```

### Workflow d'adhésion (simplifié, sans table séparée)

1. Visiteur remplit le formulaire d'adhésion sur le site vitrine
2. Compte créé immédiatement dans `utilisateurs` avec rôle `membre` et statut `en_attente`
3. Email automatique au candidat : « demande reçue »
4. Notification interne aux admins
5. Admin examine la demande dans le backoffice
6. **Si validation** → statut passe à `actif`, identifiants finaux envoyés par email
7. **Si refus** → statut passe à `rejete`, motif envoyé par email
8. À tout moment l'admin peut suspendre (`suspendu`) ou réactiver un membre

---

## Modules — Liste complète

### A. ESPACE PUBLIC

#### 1. Site vitrine (toutes pages avec contenu dynamique depuis le backoffice)

- **Accueil** — hero slider + sections éditables
- **À propos** (page parente) avec 5 sous-pages :
  - Mission
  - Vision
  - Valeurs
  - Équipe dirigeante
  - Histoire de la FAACI
- **Activités** — domaines d'action
- **Événements publics** — calendrier ouvert
- **Actualités** — articles et communiqués
- **Galerie** — albums photos
- **Contact** — formulaire + infos
- **Demande d'adhésion** — formulaire

#### 2. Hero Slider
- Plusieurs slides éditables depuis le backoffice
- Chaque slide contient : **image de fond**, titre, sous-titre, description, libellés des boutons CTA et leurs liens
- Ordre des slides modifiable
- Activation/désactivation d'une slide sans suppression
- Auto-play configurable

#### 3. Compteur de visites
- Tracking automatique des visites du site vitrine (chaque chargement de page = 1 visite)
- Compteur de **visites totales cumulées** (pas de distinction visiteur unique en V1)
- **Affichage public** : badge flottant fixé en bas (position `fixed bottom`), visible sur toutes les pages du site vitrine
- Stats détaillées dans le dashboard admin (visites totales, par page, par jour)
- Exclusion automatique des admins et membres connectés du comptage
- Cache du compteur (5 min) pour éviter les requêtes BDD à chaque affichage

---

### B. ESPACE MEMBRE (authentifié, statut `actif` requis)

#### 4. Annuaire des membres
- Profils enrichis (photo, bio, promotion AIESEC, **comité local**, secteur, ville, compétences)
- Recherche et filtres avancés
- Mise en relation
- Badge "vérifié"

#### 4bis. Annuaire entreprises Alumni
- Fiches entreprises créées et rattachées à un membre (1 membre = N entreprises)
- Champs : nom, secteur, description, localisation, site web, téléphone, email, année création, logo
- Workflow : soumission membre → validation admin → publication dans l'annuaire
- Statuts : `en_attente`, `actif`, `rejete`, `inactif`
- Modification soumise à re-validation si l'entreprise était active
- CRUD complet backoffice admin (valider, rejeter, désactiver, réactiver)
- Table : `entreprises`
- Modèle : `App\Models\Entreprise`

#### 5. Projets à financer

#### 6. Projets à financer
- Soumission de projet (titre, description, images, documents)
- Type de financement : budget fixe ou ouvert
- Cycle de vie : `brouillon → en_attente → valide → en_financement → finance → en_cours → termine` (+ `rejete`)
- Validation admin obligatoire

#### 7. Module investissement (CRITIQUE)
**⚠️ Aucun paiement en ligne — tout est externe et validé manuellement.**

> **Renommage** : anciennement "contribution", désormais appelé **investissement** dans l'UI et dans la BDD (table `investissements`). Le modèle s'appelle toujours `Contribution` (PHP) mais `$table = 'investissements'`.

Workflow :
1. Membre crée une promesse d'investissement sur un projet
2. Statut → `pending`
3. Paiement effectué hors plateforme (cash, Orange Money, Wave, virement)
4. Membre déclare le paiement avec preuve optionnelle
5. Admin valide manuellement
6. Statut → `paid`

Statuts investissement : `pending`, `confirmed`, `paid`, `partial`, `cancelled`

#### 7bis. Module Dons / Contributions à la fondation
Un membre peut faire un don libre à la fondation (indépendant de tout projet).

- **Nature du don** : `argent`, `materiel`, `autre`
- Si argent : champs montant (FCFA) + moyen de paiement
- Si matériel/autre : champ valeur estimée (texte libre)
- Preuve optionnelle (image ou PDF)
- Validation manuelle par admin (statuts : `en_attente`, `confirme`, `rejete`, `annule`)
- Table : `dons`
- Modèle : `App\Models\Don`

#### 8. Opportunités d'affaires
- Appel d'offres, partenariat, sous-traitance, fournisseurs
- Modération admin
- Durée de publication limitée, renouvelable

#### 9. Offres d'emploi
- Publication par entreprises Alumni ou membres
- CDI, CDD, Stage, Freelance, Alternance
- Candidature interne (CV + lettre) ou redirection externe

#### 10. Calendrier événements & pitchs
- Vue calendrier + liste
- Types : Réunion, Pitch, Webinaire, Networking, AG
- Inscription, capacité limitée, rappels email

#### 11. Dashboard membre
- Stats personnelles
- Mes projets, mes contributions
- Opportunités et événements pertinents
- Notifications
- Profil completeness

#### 12bis. Système de notifications membres *(implémenté)*

**Notifications événements (automatiques)**
- Déclenchées via `EvenementObserver::saved()` dès qu'un événement passe en `publie`
- Rappels J-7 et J-1 via commande schedulée `php artisan notifier:evenements` (daily 08:00)
- Canal `database` (table `notifications` Laravel native)
- Classe : `App\Notifications\EvenementAVenir`
- Lien direct vers la page de l'événement dans l'espace membre

**Annonces admin → membres (bulletin board)**
- Table `annonces` : `titre`, `contenu`, `type` (info/success/warning/urgent), `statut` (brouillon/publiee/archivee), `publiee_at`, `expire_at`
- CRUD complet dans le backoffice admin (sidebar "Annonces membres")
- Toutes les annonces publiées et non expirées sont visibles par tous les membres actifs
- Tracking de lecture : colonne `annonces_lues_at` sur `users` (timestamp "tout marqué lu")
- Modèle : `App\Models\Annonce`
- Controller admin : `App\Http\Controllers\Admin\AnnonceController`

**Interface membre**
- Cloche dans le topbar avec compteur non-lus (notifications + annonces nouvelles)
- Dropdown cloche : aperçu des 5 derniers items + lien "Voir tout"
- Page complète `/espace-membre/notifications` : annonces admin + historique notifications événements
- Lien "Notifications" dans la sidebar avec badge compteur
- "Tout marquer lu" : marque les notifications lues + met à jour `annonces_lues_at`
- Controller membre : `App\Http\Controllers\Membre\NotificationController`

---

### C. BACKOFFICE ADMIN

#### 12. Gestion des membres (avec statuts)
- Liste des membres avec filtres par statut
- Validation des demandes `en_attente` → `actif`
- Refus avec motif → `rejete`
- Suspension d'un actif → `suspendu`
- Réactivation d'un `suspendu` ou `inactif` → `actif`
- Désactivation manuelle → `inactif`
- Historique des changements de statut (logs)
- Attribution / retrait de rôles (admin, super_admin)

#### 13. Gestion du site vitrine (CMS interne)
Tout le contenu public est éditable depuis le backoffice :
- **Slides du hero** : CRUD complet avec upload d'image, ordre, statut actif/inactif
- **Sections de la page d'accueil** : textes, chiffres clés, blocs éditables
- **À propos** : édition de chaque sous-page (Mission, Vision, Valeurs, Équipe, Histoire)
- **Équipe dirigeante** : CRUD des membres avec photo, nom, fonction, bio, ordre d'affichage
- **Valeurs** : CRUD avec icône, titre, description
- **Activités** : CRUD avec icône, titre, description
- **Événements** : CRUD avec image, type, dates, lieu, inscription
- **Actualités** : CRUD articles avec image, catégorie, contenu riche
- **Galerie** : CRUD albums + upload multiple d'images
- **Paramètres du site** : email, téléphone, adresse, réseaux sociaux, textes globaux (header, footer)

#### 14. Modération
- Validation projets et changement de statut
- Validation manuelle des paiements
- Modération opportunités, offres, entreprises
- Modération candidatures emploi

#### 15. Statistiques globales
- KPIs : membres actifs/suspendus/inactifs, projets, fonds mobilisés, événements
- Top contributeurs
- Statistiques de visite du site
- Suivi projets financés

#### 16. Logs d'activité
- Toute action admin tracée (validations, suspensions, modifs contenu)
- Actions sensibles membres également

#### 17. Export de données
- Export CSV / Excel : membres, contributions, projets, événements

---

## Exigences techniques

- Architecture modulaire (séparation par modules Laravel)
- API REST interne pour les composants dynamiques
- Sécurité : auth Laravel + CSRF + validation Form Requests + rate limiting
- Pagination server-side via **Yajra DataTables** sur toutes les listes
- Cache des pages publiques + invalidation automatique par Observers à chaque modif admin
- Logs d'activité (Spatie Activity Log)
- HTTPS obligatoire en production
- Backup BDD quotidien automatique

---

## UX / Design

- Style institutionnel ONG / professionnel
- Dashboard moderne type fintech pour l'espace membre
- Interface simple, intuitive, accessible
- Mobile-first
- **Framework UI** : Bootstrap 5.3 (composants natifs + overrides custom pour la palette FAACI)
- **Polices** importées via Google Fonts (Playfair Display + DM Sans)
- **Icônes** : Bootstrap Icons (cohérent avec Bootstrap)
- **un template html** existant utilise ça pour travailler template.html

---

## Multilingue

- Français par défaut
- Structure i18n Laravel (`lang/fr`, `lang/en`) prête dès la V1
- Anglais activé en V2

---

## Entités principales (DB)

```
─── Utilisateurs & accès ───
utilisateurs                  → membres et admins (avec champ statut)

─── Espace membre ───
entreprises                   → fiches entreprises Alumni (liées à un membre propriétaire, validation admin)
projets                       → projets à financer
investissements               → promesses d'investissement sur projets (ancienne table contributions)
opportunites                  → opportunités d'affaires
offres_emploi                 → offres de poste
candidatures                  → postulations aux offres d'emploi
evenements                    → agenda et pitchs
inscriptions_evenements       → participants

─── Contenu public (CMS) ───
slides                        → hero slider (image, titre, texte, boutons, ordre, actif)
contenus_sections             → blocs de contenu éditables (clé / valeur / type)
parametres_site               → email, tel, adresse, réseaux sociaux, textes globaux
valeurs                       → blocs valeurs (icône, titre, description)
membres_equipe                → équipe dirigeante (photo, nom, fonction, bio, ordre)
articles                      → actualités
albums_galerie                → albums photo
images_galerie                → photos liées aux albums

─── Système ───
notifications                 → notifications natives Laravel
logs_activite                 → journal d'activité
medias                        → fichiers (Spatie MediaLibrary)
dons                          → dons à la fondation (argent, matériel, autre) avec validation admin
visites                       → tracking des visites du site vitrine
```

---

## Règles de développement à retenir

1. **Tables, colonnes, routes en français** — convention métier du projet
2. **Yajra DataTables** sur toutes les listes longues
3. **Spatie MediaLibrary** pour tout upload d'image ou fichier
4. **Spatie Permission** pour toute vérification de rôle (mais l'accès effectif dépend AUSSI du statut)
5. **Cache + Observers** pour les données fréquemment lues (pages publiques, slider, contenus)
6. **Aucun paiement en ligne** — tout est validation manuelle
7. **Mobile first** systématiquement
8. **`composer install` seulement** côté serveur, jamais `composer update`
9. **Validation admin obligatoire** pour : adhésion (changement de statut), projet, contribution, entreprise, opportunité, offre d'emploi
10. **Tout contenu public est éditable depuis le backoffice** — aucune chaîne en dur côté site vitrine (sauf labels d'interface)
11. **Compteur de visites** actif dès la V1, exclusion automatique des admins
12. **Middleware combiné rôle + statut** pour protéger l'espace membre : `auth + role:membre + statut:actif`
13. **utilisez les tycatch dans les controllers**


---

## Plan de développement recommandé

1. **Phase 1 — Socle technique** : Laravel installé, BDD complète, Auth, rôles Spatie, gestion des statuts, layouts
2. **Phase 2 — Backoffice CMS** : gestion slides, sections accueil, à propos (5 sous-pages), équipe, valeurs, paramètres site
3. **Phase 3 — Site vitrine dynamique** : accueil + à propos (et sous-pages) + contact + galerie + actualités + événements publics, alimentés par le CMS
4. **Phase 4 — Compteur de visites** + stats admin
5. **Phase 5 — Adhésion + gestion membres** : formulaire public + workflow admin de validation/suspension
6. **Phase 6 — Espace membre** : auth, annuaire, profil, dashboard membre
7. **Phase 7 — Entreprises Alumni**
8. **Phase 8 — Projets & financement**
9. **Phase 9 — Opportunités & emplois**
10. **Phase 10 — Événements & calendrier (côté membre)**
11. **Phase 11 — Tests, recette, déploiement**


**je veux les retours claude en francais**



