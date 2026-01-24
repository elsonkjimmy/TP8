# Script de Mise en Place de la Documentation

## 🚀 Fichiers à Placer dans Documentation/

Les fichiers suivants ont été créés à la racine du projet :
- CAHIER_CONCEPTION_IMPLEMENTATION.md
- SETUP.md
- TECHNICAL_DOCUMENTATION.md
- VALIDATION_CHECKLIST.md
- DOCUMENTATION_INDEX.md

## Copiez-les dans le dossier Documentation/

### Option 1 : Copie Manuelle (Simple)

1. Ouvrez l'Explorateur de Fichiers
2. Accédez à : `d:\TP8\gestion-academique\`
3. Créez un dossier `Documentation` (déjà créé ✓)
4. Sélectionnez les 5 fichiers .md à la racine
5. Coupez (Ctrl+X)
6. Allez dans le dossier `Documentation\`
7. Collez (Ctrl+V)

**Résultat :**
```
d:\TP8\gestion-academique\Documentation\
├── README.md
├── GUIDE_CONVERSION_PDF.md
├── CAHIER_CONCEPTION_IMPLEMENTATION.md
├── SETUP.md
├── TECHNICAL_DOCUMENTATION.md
├── VALIDATION_CHECKLIST.md
└── DOCUMENTATION_INDEX.md
```

### Option 2 : Copie via Terminal (Rapide)

**Windows (PowerShell) :**
```powershell
# Naviguer au dossier
cd d:\TP8\gestion-academique

# Copier les fichiers
Copy-Item "CAHIER_CONCEPTION_IMPLEMENTATION.md" "Documentation\"
Copy-Item "SETUP.md" "Documentation\"
Copy-Item "TECHNICAL_DOCUMENTATION.md" "Documentation\"
Copy-Item "VALIDATION_CHECKLIST.md" "Documentation\"
Copy-Item "DOCUMENTATION_INDEX.md" "Documentation\"

# Vérifier
Get-ChildItem Documentation\
```

**macOS/Linux :**
```bash
cd /chemin/vers/gestion-academique

cp CAHIER_CONCEPTION_IMPLEMENTATION.md Documentation/
cp SETUP.md Documentation/
cp TECHNICAL_DOCUMENTATION.md Documentation/
cp VALIDATION_CHECKLIST.md Documentation/
cp DOCUMENTATION_INDEX.md Documentation/

ls -la Documentation/
```

---

## ✅ Vérification

Vous devez avoir dans `Documentation/` :

```
Documentation/
├── README.md                                  ← Navigation
├── GUIDE_CONVERSION_PDF.md                    ← Guide PDF
├── CAHIER_CONCEPTION_IMPLEMENTATION.md        ← 📄 Principal
├── SETUP.md                                   ← Installation rapide
├── TECHNICAL_DOCUMENTATION.md                 ← Architecture
├── VALIDATION_CHECKLIST.md                    ← Validation
└── DOCUMENTATION_INDEX.md                     ← Index complet
```

---

## 🎯 Prochaines Étapes

### 1. Vérifiez les fichiers sont dans Documentation/
```bash
ls -la Documentation/  # macOS/Linux
dir Documentation\     # Windows
```

### 2. Convertissez en PDF

Voir le fichier `GUIDE_CONVERSION_PDF.md` pour les instructions détaillées.

**Méthode rapide (Pandoc) :**
```bash
cd Documentation

# Windows
pandoc CAHIER_CONCEPTION_IMPLEMENTATION.md -o CAHIER_CONCEPTION_IMPLEMENTATION.pdf --toc

# Ou tous les fichiers
pandoc CAHIER_CONCEPTION_IMPLEMENTATION.md -o CAHIER_CONCEPTION_IMPLEMENTATION.pdf
pandoc SETUP.md -o SETUP.pdf
pandoc TECHNICAL_DOCUMENTATION.md -o TECHNICAL_DOCUMENTATION.pdf
pandoc VALIDATION_CHECKLIST.md -o VALIDATION_CHECKLIST.pdf
pandoc DOCUMENTATION_INDEX.md -o DOCUMENTATION_INDEX.pdf
```

### 3. Remettez le devoir

Vous pouvez maintenant remettre :
- Le dossier `Documentation/` avec tous les fichiers
- Les PDFs générés (si demandés)

---

**Note :** Les fichiers ont déjà été créés à la racine du projet pour plus de flexibilité. 
Vous pouvez les garder à la fois à la racine ET dans le dossier Documentation, ou seulement dans Documentation si vous préférez.
