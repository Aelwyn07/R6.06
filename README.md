# Calendrier prévisionnel

Application de gestion de calendrier provisoire développée avec Laravel et Vue.js.

## Prérequis

- Docker Desktop
- Composer
- Node.js (v18+)
- Git

## Installation

1. **Cloner le projet**
```bash
git clone https://github.com/0wme/ProvisionalCalendar.git
cd provisionnal-calendar
```

2. **Configuration initiale**

Créez le fichier d'environnement :
```bash
cp .env.example .env
```

Configurez l'alias Sail (facultatif mais recommandé) :
```bash
# Pour macOS (dans ~/.zshrc)
# Pour Linux (dans ~/.bashrc)
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

3. **Installation des dépendances**
```bash
composer install
```

## Démarrage

1. **Lancer l'environnement Docker**
```bash
sail up -d
```

2. **Configuration de la base de données**
```bash
sail artisan key:generate
sail artisan migrate
```

3. **Installation et compilation des assets**
```bash
sail npm install
```

L'application est maintenant accessible à l'adresse : http://localhost

## Développement

- **Lancer le serveur de développement**
```bash
sail npm run dev
```

- **Lancer les tests**
```bash
sail artisan test
```

- **Accéder à la base de données**
```bash
sail mariadb
```

## Commandes utiles

- **Arrêter l'environnement**
```bash
sail down
```

- **Voir les logs**
```bash
sail logs
```

- **Lancer un shell dans le conteneur**
```bash
sail shell
```

## Résolution des problèmes courants

### Erreur : Access denied for user 'sail'@'<ip>' (using password: YES)

Cette erreur survient lorsqu'un volume Docker existant entre en conflit.

Solution :
```bash
# Supprimer le volume existant
docker volume rm sail-mariadb

# Redémarrer l'environnement
sail down
sail up -d
```

### Les modifications Vue.js ne sont pas prises en compte

1. Vérifiez que le serveur Vite est en cours d'exécution :
```bash
sail npm run dev
```

2. Si le problème persiste, essayez de :
```bash
sail npm cache clean --force
sail npm install
sail npm run dev
```

## Liens utiles

- [PHP Documentation](https://www.php.net/)
- [Composer Documentation](https://getcomposer.org/)
- [Laravel Documentation](https://laravel.com/)
- [PHPUnit Documentation](https://phpunit.de/)

- [Inertia.js Documentation](https://inertiajs.com/)
- [Ziggy Documentation](https://github.com/tighten/ziggy)

- [Node.js Documentation](https://nodejs.org/)
- [Vue.js Documentation](https://vuejs.org/)
- [Vue Test Utils Documentation](https://test-utils.vuejs.org/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)

- [Docker Documentation](https://www.docker.com/)
