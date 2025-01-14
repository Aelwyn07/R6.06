<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notification de modification</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="color: #2c3e50; font-size: 24px; margin: 0;">Notification de Modification</h1>
        </div>
        
        <div style="padding: 20px; background-color: #f8f9fa; border-radius: 6px; margin-bottom: 20px;">
            <p style="color: #2c3e50; font-size: 16px; line-height: 1.5; margin-top: 0;">
                Bonjour {{ $teacher->firstname }},
            </p>
            <p style="color: #2c3e50; font-size: 16px; line-height: 1.5;">
                Des modifications ont été apportées à vos informations dans le système de gestion des emplois du temps.
            </p>
            <div style="text-align: center; margin-top: 25px;">
                <a href="{{ url('/calendrier-previsionnel/editeur') }}" style="background-color: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Voir les modifications</a>
            </div>
        </div>
        
        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #eee;">
            <p style="color: #666; font-size: 14px; margin: 0;">
                Ceci est un message automatique, merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>