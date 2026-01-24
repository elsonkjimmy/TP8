# 🔄 GUIDE CONVERSION MARKDOWN → PDF

## Plusieurs Méthodes Disponibles

### ✅ Méthode 1 : Utiliser Pandoc (Recommandé)

Pandoc est l'outil le plus puissant pour convertir du Markdown en PDF.

#### Installation

**Windows :**
```bash
# Via Chocolatey
choco install pandoc

# OU via l'installateur
# Télécharger depuis : https://pandoc.org/installing.html
```

**macOS :**
```bash
brew install pandoc
```

**Linux :**
```bash
sudo apt-get install pandoc
```

#### Conversion Simple

```bash
# Convertir un fichier
pandoc input.md -o output.pdf

# Exemple complet
pandoc CAHIER_CONCEPTION_IMPLEMENTATION.md -o CAHIER_CONCEPTION_IMPLEMENTATION.pdf
```

#### Conversion avec Style

```bash
# Avec CSS personnalisé
pandoc CAHIER_CONCEPTION_IMPLEMENTATION.md \
  -o CAHIER_CONCEPTION_IMPLEMENTATION.pdf \
  --variable mainfont="Calibri" \
  --variable fontsize=11pt \
  --pdf-engine=xelatex

# Avec table des matières
pandoc CAHIER_CONCEPTION_IMPLEMENTATION.md \
  -o CAHIER_CONCEPTION_IMPLEMENTATION.pdf \
  --toc \
  --toc-depth=2 \
  --pdf-engine=xelatex
```

#### Batch Conversion (Tous les fichiers à la fois)

Créez un script `convert_all.sh` :

**Sur Windows (PowerShell) :**
```powershell
# Créer convert_all.ps1
$files = @(
    "CAHIER_CONCEPTION_IMPLEMENTATION.md",
    "SETUP.md",
    "TECHNICAL_DOCUMENTATION.md",
    "VALIDATION_CHECKLIST.md",
    "DOCUMENTATION_INDEX.md"
)

foreach ($file in $files) {
    $output = $file -replace '\.md$', '.pdf'
    Write-Host "Converting $file to $output..."
    pandoc $file -o $output --toc --pdf-engine=xelatex
}

Write-Host "Conversion terminée!"
```

Puis exécuter :
```powershell
.\convert_all.ps1
```

**Sur macOS/Linux :**
```bash
#!/bin/bash

files=(
    "CAHIER_CONCEPTION_IMPLEMENTATION.md"
    "SETUP.md"
    "TECHNICAL_DOCUMENTATION.md"
    "VALIDATION_CHECKLIST.md"
    "DOCUMENTATION_INDEX.md"
)

for file in "${files[@]}"
do
    output="${file%.md}.pdf"
    echo "Converting $file to $output..."
    pandoc "$file" -o "$output" --toc --pdf-engine=xelatex
done

echo "Conversion terminée!"
```

Puis exécuter :
```bash
chmod +x convert_all.sh
./convert_all.sh
```

---

### ✅ Méthode 2 : Utiliser VS Code (Simple et Rapide)

#### Extension Markdown PDF

1. **Installer l'extension**
   - Ouvrez VS Code
   - Allez à Extensions (Ctrl+Shift+X)
   - Recherchez "Markdown PDF"
   - Installez `yzane.markdown-pdf`

2. **Convertir un fichier**
   - Ouvrez le fichier .md
   - Clic droit sur l'onglet
   - "Markdown PDF: Export"
   - Choisir le format (PDF)

3. **Convertir tous les fichiers**
   - Allez à File → Preferences → Settings
   - Recherchez "markdown-pdf"
   - Configure les options de style

#### Configuration VS Code (.vscode/settings.json)

```json
{
    "markdown-pdf.type": "pdf",
    "markdown-pdf.format": "A4",
    "markdown-pdf.border.top": "1cm",
    "markdown-pdf.border.bottom": "1cm",
    "markdown-pdf.border.left": "1cm",
    "markdown-pdf.border.right": "1cm",
    "markdown-pdf.highlight": true,
    "markdown-pdf.breaks": false
}
```

---

### ✅ Méthode 3 : Utiliser GitHub (Gratuit, en Ligne)

1. **Pousser vers GitHub**
   ```bash
   git push origin main
   ```

2. **Convertir en ligne**
   - URL : https://md2pdf.netlify.app/
   - Coller votre fichier Markdown
   - Télécharger le PDF

---

### ✅ Méthode 4 : Utiliser Google Chrome

1. **Ouvrir le fichier Markdown en HTML**
   - Installer : https://chrome.google.com/webstore/detail/markdown-viewer/ckkdlimhmcjmikdlpkmbgfkaikjcaljf
   - Ouvrir le fichier .md avec Chrome

2. **Imprimer en PDF**
   - Ctrl+P
   - Destination : "Enregistrer en tant que PDF"
   - Cliquer "Enregistrer"

---

### ✅ Méthode 5 : Utiliser Node.js (Pour Développeurs)

#### Installation

```bash
npm install -g markdown-pdf
```

#### Conversion

```bash
# Convertir un fichier
markdown-pdf CAHIER_CONCEPTION_IMPLEMENTATION.md

# Avec options
markdown-pdf CAHIER_CONCEPTION_IMPLEMENTATION.md \
  --out . \
  --format A4 \
  --quality 100
```

---

## 📋 COMPARAISON DES MÉTHODES

| Méthode | Facilité | Qualité | Installation |
|---------|----------|---------|--------------|
| **Pandoc** | Moyenne | ⭐⭐⭐⭐⭐ (Excellente) | CLI |
| **VS Code** | ⭐⭐⭐⭐⭐ (Très facile) | ⭐⭐⭐⭐ (Bonne) | Extension |
| **GitHub** | ⭐⭐⭐⭐⭐ (En ligne) | ⭐⭐⭐ (Moyenne) | Aucune |
| **Chrome** | ⭐⭐⭐⭐ (Facile) | ⭐⭐⭐⭐ (Bonne) | Browser |
| **Node.js** | Moyenne | ⭐⭐⭐⭐ (Bonne) | npm |

---

## 🚀 RECOMMANDATION

**Pour vos documents :** **Utilisez Pandoc** (Méthode 1)
- Meilleure qualité
- Options avancées
- Rendu professionnel
- Peut générer plusieurs PDFs en batch

---

## 📝 EXEMPLE COMPLET

### Installation (Une seule fois)

```bash
# Windows (Chocolatey)
choco install pandoc

# OU macOS
brew install pandoc
```

### Conversion des fichiers

```bash
# Naviguer au dossier Documentation
cd Documentation

# Convertir les fichiers principaux
pandoc CAHIER_CONCEPTION_IMPLEMENTATION.md -o CAHIER_CONCEPTION_IMPLEMENTATION.pdf --toc --pdf-engine=xelatex
pandoc SETUP.md -o SETUP.pdf --toc --pdf-engine=xelatex
pandoc TECHNICAL_DOCUMENTATION.md -o TECHNICAL_DOCUMENTATION.pdf --toc --pdf-engine=xelatex
pandoc VALIDATION_CHECKLIST.md -o VALIDATION_CHECKLIST.pdf --toc --pdf-engine=xelatex
pandoc DOCUMENTATION_INDEX.md -o DOCUMENTATION_INDEX.pdf --toc --pdf-engine=xelatex
```

### Résultat

```
Documentation/
├── CAHIER_CONCEPTION_IMPLEMENTATION.md
├── CAHIER_CONCEPTION_IMPLEMENTATION.pdf  ✅
├── SETUP.md
├── SETUP.pdf  ✅
├── TECHNICAL_DOCUMENTATION.md
├── TECHNICAL_DOCUMENTATION.pdf  ✅
└── ...
```

---

## ⚙️ TROUBLESHOOTING

### Erreur : "pandoc: command not found"
**Solution :** Installer Pandoc (voir Installation ci-dessus)

### Erreur : "xelatex not found"
**Solution :** 
```bash
# Installer MiKTeX (Windows/Mac) ou TeX Live (Linux)
# Windows : https://miktex.org/download
# macOS : brew install mactex
# Linux : sudo apt-get install texlive
```

### PDF sans Table des matières
**Solution :** Ajouter `--toc` à la commande Pandoc

### Polices non reconnues
**Solution :** Utiliser une police standard
```bash
pandoc input.md -o output.pdf --variable mainfont="Calibri"
```

---

## 💡 CONSEILS

1. **Pour la remise :** Générez uniquement `CAHIER_CONCEPTION_IMPLEMENTATION.pdf`
2. **Pour les autres :** Vous pouvez laisser en format .md
3. **Pour l'impression :** Assurez-vous que le PDF se rend bien

---

**Générés le :** 24 Janvier 2026
**Version :** 1.0
