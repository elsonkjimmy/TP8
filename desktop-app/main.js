const { app, BrowserWindow } = require('electron');
const path = require('path');

function createWindow() {
    const win = new BrowserWindow({
        width: 1280,
        height: 800,
        webPreferences: {
            nodeIntegration: true,
            contextIsolation: false
        }
    });

    // URL de votre application Laravel
    const laravelUrl = 'http://localhost:8001';
    
    // Tente de charger l'URL Laravel
    win.loadURL(laravelUrl).catch((err) => {
        console.log('Serveur Laravel non accessible, chargement de la version statique (TP8).');
        // En cas d'erreur, charge le fichier local (votre TP8.html copié en index.html)
        win.loadFile('index.html');
    });
}

app.whenReady().then(() => {
    createWindow();

    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) {
            createWindow();
        }
    });
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});
