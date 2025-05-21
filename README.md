# SJ4WEB - OpCache Monitor

Module PrestaShop pour afficher en temps réel l'état de l'OpCache PHP directement dans le back-office.

---

## 📦 Fonctionnalités

- Affiche la **configuration OpCache** (`opcache_get_configuration()`).
- Affiche le **statut en temps réel** (`opcache_get_status()`).
- Bouton pour **vider le cache OpCache** à la demande.
- Intégration complète dans l’administration PrestaShop.
- Accès sécurisé via l’interface du back-office (aucune page publique exposée).

---

## ✅ Compatibilité

- PrestaShop **1.7.x** à **8.1.x**
- PHP ≥ **7.3** avec **OpCache activé**
- Affichage compatible avec l’interface Bootstrap du BO PrestaShop

---

## ⚙️ Installation

1. Copier le dossier `sj4webopcachemonitor` dans `/modules/` de votre site PrestaShop.
2. Aller dans **Modules > Module Manager** puis installer le module `SJ4WEB - OpCache Monitor`.
3. Le module ajoutera un lien dans le menu **"Paramètres avancés"** ou sera accessible depuis la liste des modules.
4. Cliquez sur **"Configurer"** pour afficher :
    - la **configuration actuelle** d’OpCache,
    - les **statistiques en temps réel**,
    - un **bouton pour vider le cache** si nécessaire.


---

## 🛡 Sécurité

- Toutes les informations sont accessibles **uniquement depuis le back-office**.
- Aucun endpoint ou page publique n'est exposée.
- Pas d’API externe ni de dépendance tierce.

---

## 🛠 Prérequis techniques

- PHP avec l’extension **OpCache activée**
- Les fonctions PHP suivantes doivent être disponibles :
    - `opcache_get_status()`
    - `opcache_get_configuration()`
    - `opcache_reset()`

---

## 👤 Auteur

**SJ4WEB.FR**  
Développement sur mesure pour PrestaShop

---

## 📜 Licence

Usage privé réservé à SJ4WEB ou à ses clients.  
Reproduction ou distribution non autorisée sans accord préalable.

