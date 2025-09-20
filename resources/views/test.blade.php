<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Gestion Kourel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: white;
            font-size: 32px;
            font-weight: bold;
        }
        h1 {
            color: #1f2937;
            font-size: 2.5rem;
            margin: 0;
        }
        .subtitle {
            color: #6b7280;
            font-size: 1.2rem;
            margin-top: 10px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        .stat-label {
            color: #6b7280;
            font-size: 1.1rem;
        }
        .status {
            background: #10b981;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .info {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        .info h3 {
            color: #0369a1;
            margin-top: 0;
        }
        .info p {
            color: #0c4a6e;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">DK</div>
            <h1>Gestion Kourel</h1>
            <p class="subtitle">Plateforme moderne de gestion de dahira/kourel</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">4</div>
                <div class="stat-label">Membres</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">500K</div>
                <div class="stat-label">FCFA Cotisations</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div class="stat-label">Activités</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">2</div>
                <div class="stat-label">Événements</div>
            </div>
        </div>

        <div class="status">
            ✅ Application opérationnelle - Laravel {{ app()->version() }}
        </div>

        <div class="info">
            <h3>🚀 Fonctionnalités disponibles :</h3>
            <p>• Gestion des membres avec profils complets</p>
            <p>• Suivi des cotisations et paiements</p>
            <p>• Planification des activités (répétitions, prestations)</p>
            <p>• Gestion des événements (Magal, Gamou, Promokhane)</p>
            <p>• Système d'alertes automatiques</p>
            <p>• Interface moderne et responsive</p>
        </div>
    </div>

    <script>
        console.log('🚀 Gestion Kourel - Application test chargée avec succès !');
        console.log('📊 Version Laravel:', '{{ app()->version() }}');
        console.log('🌍 Environnement:', '{{ app()->environment() }}');
        console.log('⏰ Timestamp:', new Date().toISOString());
    </script>
</body>
</html>
