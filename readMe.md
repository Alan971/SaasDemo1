# Tutoriel de création d'une application SaaS avec Symfony et Stripe

Ce tutoriel vous guide dans la création d'une application SaaS avec Symfony, y compris la gestion de l'authentification des utilisateurs, la vérification des emails, l'intégration de Tailwind CSS, la génération de lettres de motivation avec OpenAI et la mise en place des paiements avec Stripe.

## Installation de Symfony

1. Créez un nouveau projet Symfony :

    ```bash
    symfony new tutoSaas --webapp
    cd tutoSaas
    ```

2. Démarrez le Docker Symfony pour obtenir une base de données et un mailer :

    ```bash
    docker-compose up -d
    ```

## Création d'un utilisateur

1. Créez un utilisateur :

    ```bash
    symfony console make:user
    ```

## Mise en place de la vérification des emails

1. Installez le package pour la vérification des emails :

    ```bash
    composer require symfonycasts/verify-email-bundle
    ```

## Mise en place du formulaire d'enregistrement

1. Créez le formulaire d'enregistrement :

    ```bash
    symfony console make:registration-form
    ```

    Ce formulaire permet de vérifier l'email en envoyant un email de confirmation.

2. Pour la démonstration, utilisez l'email `test@test.com`. Vous pouvez changer cette adresse plus tard. Le nom "Bot" est utilisé pour l'envoi des emails.

## Création du formulaire de connexion

1. Créez le formulaire de connexion :

    ```bash
    symfony console make:auth
    ```

    Choisissez l'option **1. Login form Authenticator**.

    - Nom de classe : `AppAuthenticator`
    - Nom du contrôleur : `SecurityController`

    > **Note :** `make:auth` est déprécié, il est préférable d'utiliser `make:security`.

## Migration des données

1. Créez une migration pour la base de données :

    ```bash
    symfony console make:migration
    ```

2. Exécutez la migration :

    ```bash
    symfony console doctrine:migrations:migrate
    ```

## Test d'enregistrement

1. Ouvrez la page d'enregistrement :

    ```bash
    http://127.0.0.1:8000/register
    ```

2. Entrez les informations suivantes :

    - Email : `test@test.com`
    - Mot de passe : `test`

3. Après avoir validé, une erreur s'affiche car la page d'accueil n'existe pas encore. Toutefois, dans la debug bar de Symfony, vous verrez qu'un email est en cours d'envoi (en attente dans la queue).

4. Dans la debug bar, recherchez **sfServer** pour ouvrir la boîte mail (`mailpit` dans le container Docker) et exécutez la commande suivante pour consommer le message :

    ```bash
    symfony console messenger:consume -vv
    ```

5. Vous recevrez l'email de confirmation, cliquez sur le lien et vous pourrez maintenant vous connecter à l'application via :

    ```bash
    http://127.0.0.1:8000/login
    ```

## Création de la page d'accueil

1. Créez le contrôleur pour la page d'accueil :

    ```bash
    symfony console make:controller Home
    ```

2. Installez Tailwind CSS sur Symfony en suivant la procédure officielle :
   
    - Supprimez les bundles inutiles :

        ```bash
        composer remove symfony/ux-turbo symfony/asset-mapper symfony/stimulus-bundle
        ```

    - Installez les dépendances nécessaires :

        ```bash
        composer require symfony/webpack-encore-bundle symfony/ux-turbo symfony/stimulus-bundle
        ```

    - Installez les paquets NPM pour Tailwind CSS :

        ```bash
        npm install tailwindcss @tailwindcss/postcss postcss postcss-loader
        ```

3. Suivez rigoureusement la procédure de configuration de Tailwind CSS disponible [ici](https://tailwindcss.com/docs/guides/symfony).

## Création d'une barre de navigation

Créez une barre de navigation avec Tailwind CSS et Symfony, puis modifiez le fichier `base.html.twig` pour ajouter votre template. Vous pouvez utiliser un template de la section des landing pages de Tailwind CSS :

- [Exemple de page de marketing avec Tailwind CSS](https://tailwindcss.com/components/marketing/page-examples/landing-pages)

## Création du formulaire pour générer une lettre de motivation

1. Créez un contrôleur pour gérer le formulaire :

    ```bash
    symfony console make:controller AppController
    ```

2. Créez le formulaire pour la lettre de motivation :

    ```bash
    symfony console make:form CoverType
    ```

3. Modifiez le formulaire dans le contrôleur pour le rendre esthétique. Utilisez la fonction `dd($data)` pour afficher le contenu du formulaire dans la page web.

## Génération d'une lettre de motivation avec OpenAI

1. Installez la bibliothèque OpenAI PHP :

    v
    composer require openai-php/client
    composer require guzzlehttp/guzzle
    ```

2. Rendez-vous sur [OpenAI](https://platform.openai.com) pour obtenir une clé API.

3. Exemple de code pour générer une lettre de motivation avec OpenAI :

    ```php
    use OpenAI;

    $yourApiKey = getenv('YOUR_API_KEY');
    $client = OpenAI::client($yourApiKey);

    $result = $client->chat()->create([
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello!'],
        ],
    ]);
    ```

4. Vous pouvez aussi utiliser une autre API gratuite pour générer des lettres de motivation : [Together API](https://api.together.xyz/v1/chat/completions).

5. Les deux méthodes sont intégrées dans le fichier `services/AIs.php`.

## Désactivation de Turbo pour le formulaire

Turbo n'aime pas utiliser la même page que celle du formulaire pour afficher le résultat. Désactivez Turbo pour cette partie dans le fichier Twig :

```twig
{{ form_start(form, {'method': 'POST', 'action': path('app_app'), 'attr': {'data-turbo': 'false'}}) }}
```

## Paiements
L'accès ne peux se faire que si l'utilisateur est connecté et a payé son abonnement.
symfony console make:entity User
ajout du champs paiement 
puis on rajoute une condition dans le controller

utilisation de stripe :  
[lien1](https://docs.stripe.com/sdks)  
[lien2](https://docs.stripe.com/checkout/quickstart?lang=php)

```bash
composer require stripe/stripe-php
```
puis on crée un controller pour gérer les paiements
```bash
symfony console make:controller Stripe
```

dans ce controller on ajoute le code nécessaire à la connexion stripe
on crée 2 routes pour gérer les 2 actions paiement validé ou non

