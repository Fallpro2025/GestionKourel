#!/bin/bash

echo "================================================"
echo "🔧 Script de Correction des Photos Membres"
echo "================================================"
echo ""

# Couleurs pour l'affichage
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Créer le lien symbolique
echo -e "${YELLOW}1. Création du lien symbolique storage...${NC}"
php artisan storage:link
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Lien symbolique créé/vérifié${NC}"
else
    echo -e "${RED}❌ Erreur lors de la création du lien${NC}"
fi
echo ""

# 2. Créer le dossier photos s'il n'existe pas
echo -e "${YELLOW}2. Vérification/Création du dossier photos...${NC}"
mkdir -p storage/app/public/photos
if [ -d "storage/app/public/photos" ]; then
    echo -e "${GREEN}✅ Dossier photos existe${NC}"
else
    echo -e "${RED}❌ Impossible de créer le dossier photos${NC}"
fi
echo ""

# 3. Définir les permissions
echo -e "${YELLOW}3. Configuration des permissions...${NC}"
chmod -R 775 storage/app/public/photos 2>/dev/null
chmod 775 public/storage 2>/dev/null
echo -e "${GREEN}✅ Permissions configurées${NC}"
echo ""

# 4. Vérifier la structure
echo -e "${YELLOW}4. Vérification de la structure...${NC}"
echo "Contenu de storage/app/public/photos:"
ls -lah storage/app/public/photos 2>/dev/null || echo "  (vide ou inaccessible)"
echo ""
echo "Lien symbolique public/storage:"
ls -lah public/storage 2>/dev/null || echo "  (non trouvé)"
echo ""

# 5. Test de création d'une image de test
echo -e "${YELLOW}5. Création d'une image de test (optionnel)...${NC}"
read -p "Voulez-vous créer une image de test ? (o/N) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Oo]$ ]]; then
    # Créer une image SVG de test
    cat > storage/app/public/photos/test-membre.jpg << 'EOF'
<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
  <rect width="200" height="200" fill="#3b82f6"/>
  <text x="100" y="100" font-size="60" fill="white" text-anchor="middle" dy=".3em">TEST</text>
</svg>
EOF
    echo -e "${GREEN}✅ Image de test créée: storage/app/public/photos/test-membre.jpg${NC}"
    echo -e "${YELLOW}   Pour l'utiliser, mettez à jour la base de données:${NC}"
    echo -e "   UPDATE membres SET photo = 'photos/test-membre.jpg' WHERE id = 1;"
fi
echo ""

# 6. Résumé
echo "================================================"
echo -e "${GREEN}✅ Script terminé !${NC}"
echo "================================================"
echo ""
echo "Prochaines étapes :"
echo "1. Rafraîchissez la page http://localhost:8000/membres/1/roles"
echo "2. Vérifiez l'encadré jaune DEBUG"
echo "3. Ouvrez la console (F12) pour voir les erreurs"
echo "4. Si l'image de test a été créée, testez:"
echo "   http://localhost:8000/storage/photos/test-membre.jpg"
echo ""
echo "Besoin d'aide ? Consultez GUIDE_DIAGNOSTIC_PHOTO.md"
echo ""

