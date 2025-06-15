launch via : symfony server:start
# 🛡️ Projet Symfony – Gestion Produits & Utilisateurs

Ce projet est une application web développée avec **Symfony 6**, permettant :
- La **gestion d'utilisateurs** (création, connexion, rôles)
- La **gestion de produits** (CRUD complet)
- Un système de **notifications** pour suivre les actions dans l'application
- La **sécurité** avec système de rôles (`ROLE_USER`, `ROLE_ADMIN`)
- La **traduction** de l'interface (i18n)
- L’enregistrement automatique des dates `createdAt` / `updatedAt` via un **listener**
- Une UI basique mais fonctionnelle

## 🚀 Fonctionnalités

- 🔐 Authentification sécurisée (login / logout)
- 👤 Création de comptes utilisateurs
- 🛒 Ajout, modification, suppression de produits
- 🧾 Système de notifications généré automatiquement à chaque action
- 🧭 Redirections conditionnées selon les rôles
- 🌐 Interface traduite (multi-langue)
- 🗓️ Gestion des dates (createdAt / updatedAt) automatique via Doctrine Listener

---

## 🧪 Accès de test

🧑‍🏫 Compte administrateur pour l'évaluation :

- **Email :** `admin@admin.com`
- **Mot de passe :** `password123`

Connectez-vous via `/login`.

🧑‍🏫 Compte desactiver pour l'évaluation :

- **Email :** `no@no.fr`
- **Mot de passe :** `root`

---

enjoy ! 
