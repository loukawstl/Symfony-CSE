Pour lancer le site: 
Xamp: activer l'extension zip et intl dans Apache->Configs->php.ini
configurer les valeurs locals DATABASE_URL et MAILER_DSN dans .env.local
pour MAILER_DSN:
1. Créer un compte sur mailtrap
2. Aller dans Inboxes>My Inbox>Show Credentials pour récuperer le nom d'utilisateur et le mot de passe du compte
3. rajouter dans .env.local MAILER_DSN=smtp://[Username]:[Password]@smtp.mailtrap.io:2525/?encryption=ssl&auth_mode=login
