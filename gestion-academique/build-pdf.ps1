# Script pour générer le PDF avec la commande PRO

$fileName = "CAHIER_CONCEPTION_IMPLEMENTATION"
$inputFile = "$fileName.md"
$outputFile = "$fileName.pdf"

Write-Host "[*] Generation du PDF..." -ForegroundColor Cyan
Write-Host "Fichier source: $inputFile" -ForegroundColor Gray

pandoc $inputFile `
  --to html5 `
  --toc `
  --toc-depth=3 `
  --number-sections `
  --standalone `
  -o "_temp.html"

# Convertir HTML en PDF avec wkhtmltopdf
wkhtmltopdf --enable-local-file-access "_temp.html" $outputFile

# Nettoyer le fichier temporaire
Remove-Item "_temp.html" -Force

if ($?) {
  Write-Host "[+] PDF genere avec succes : $outputFile" -ForegroundColor Green
  Write-Host "[*] Ouverture du fichier..." -ForegroundColor Cyan
  & $outputFile
} else {
  Write-Host "[-] Erreur lors de la generation" -ForegroundColor Red
}
