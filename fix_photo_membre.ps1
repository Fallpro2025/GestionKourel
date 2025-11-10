# Script PowerShell pour corriger les photos membres
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "🔧 Script de Correction des Photos Membres" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# 1. Créer le lien symbolique
Write-Host "1. Création du lien symbolique storage..." -ForegroundColor Yellow
try {
    php artisan storage:link
    Write-Host "✅ Lien symbolique créé/vérifié" -ForegroundColor Green
} catch {
    Write-Host "❌ Erreur lors de la création du lien" -ForegroundColor Red
}
Write-Host ""

# 2. Créer le dossier photos s'il n'existe pas
Write-Host "2. Vérification/Création du dossier photos..." -ForegroundColor Yellow
$photosPath = "storage\app\public\photos"
if (!(Test-Path $photosPath)) {
    New-Item -ItemType Directory -Path $photosPath -Force | Out-Null
    Write-Host "✅ Dossier photos créé" -ForegroundColor Green
} else {
    Write-Host "✅ Dossier photos existe déjà" -ForegroundColor Green
}
Write-Host ""

# 3. Vérifier la structure
Write-Host "3. Vérification de la structure..." -ForegroundColor Yellow
Write-Host "Contenu de storage\app\public\photos:"
if (Test-Path $photosPath) {
    Get-ChildItem $photosPath | Format-Table Name, Length, LastWriteTime -AutoSize
} else {
    Write-Host "  (vide ou inaccessible)" -ForegroundColor Gray
}
Write-Host ""

Write-Host "Lien symbolique public\storage:"
if (Test-Path "public\storage") {
    Get-Item "public\storage" | Format-List
} else {
    Write-Host "  (non trouvé)" -ForegroundColor Gray
}
Write-Host ""

# 4. Vérifier le fichier .env
Write-Host "4. Vérification de la configuration..." -ForegroundColor Yellow
if (Test-Path ".env") {
    $appDebug = Select-String -Path ".env" -Pattern "APP_DEBUG"
    Write-Host "Configuration DEBUG: $appDebug"
    if ($appDebug -match "true") {
        Write-Host "✅ Mode DEBUG activé (l'encadré jaune s'affichera)" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Mode DEBUG désactivé (l'encadré jaune ne s'affichera pas)" -ForegroundColor Yellow
        Write-Host "   Pour activer: Modifiez APP_DEBUG=true dans .env" -ForegroundColor Gray
    }
} else {
    Write-Host "❌ Fichier .env non trouvé" -ForegroundColor Red
}
Write-Host ""

# 5. Test des URLs
Write-Host "5. Test d'accessibilité..." -ForegroundColor Yellow
Write-Host "URLs à tester dans votre navigateur:"
Write-Host "  - http://localhost:8000/membres/1/roles" -ForegroundColor Cyan
Write-Host "  - http://localhost:8000/storage/photos/" -ForegroundColor Cyan
Write-Host ""

# 6. Résumé
Write-Host "================================================" -ForegroundColor Cyan
Write-Host "✅ Script terminé !" -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Prochaines étapes :" -ForegroundColor White
Write-Host "1. Rafraîchissez http://localhost:8000/membres/1/roles" -ForegroundColor Gray
Write-Host "2. Vérifiez l'encadré jaune DEBUG en haut de la page" -ForegroundColor Gray
Write-Host "3. Ouvrez la console (F12) pour voir les messages" -ForegroundColor Gray
Write-Host "4. Consultez GUIDE_DIAGNOSTIC_PHOTO.md pour plus de détails" -ForegroundColor Gray
Write-Host ""
Write-Host "Commandes SQL utiles:" -ForegroundColor White
Write-Host "  SELECT id, nom, prenom, photo FROM membres WHERE id = 1;" -ForegroundColor Gray
Write-Host "  UPDATE membres SET photo = 'photos/nom-image.jpg' WHERE id = 1;" -ForegroundColor Gray
Write-Host ""

