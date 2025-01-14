<!DOCTYPE html>
<html>
<head>
    <title>Création/Réinitialisation du mot de passe</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0;
            padding: 0;
        }
        .content {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .password-box {
            background-color: #ffffff;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-family: monospace;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Votre nouveau mot de passe</h1>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            <p>Voici votre mot de passe pour accéder à l'application de gestion des calendriers prévisionnels :</p>
            
            <div class="password-box">
                {{ $password }}
            </div>
            
            <p>Vous pouvez utiliser ce mot de passe pour vous connecter à votre compte.</p>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
