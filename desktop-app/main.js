const { app, BrowserWindow } = require('electron');
const path = require('path');
const { spawn } = require('child_process');

let laravelProcess;

function startLaravelServer() {
    const laravelPath = path.join(__dirname, '../gestion-academique');
    
    // Lance le serveur Laravel sur le port 8001
    laravelProcess = spawn('php', ['artisan', 'serve', '--port=8001'], {
        cwd: laravelPath,
        shell: true
    });

    laravelProcess.stdout.on('data', (data) => {
        console.log(`Laravel: ${data}`);
    });

    laravelProcess.stderr.on('data', (data) => {
        console.error(`Laravel Error: ${data}`);
    });

    laravelProcess.on('close', (code) => {
        console.log(`Laravel server exited with code ${code}`);
    });
}

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
    
    // Fonction pour charger l'URL avec tentatives
    const loadUrlWithRetry = (retries = 10) => {
        win.loadURL(laravelUrl).catch((err) => {
            if (retries > 0) {
                console.log(`Serveur non prêt, nouvelle tentative dans 1s... (${retries} restantes)`);
                setTimeout(() => loadUrlWithRetry(retries - 1), 1000);
            } else {
                console.log('Serveur Laravel non accessible après plusieurs tentatives, chargement de la version statique.');
                win.loadFile('index.html');
            }
        });
    };

    // Attendre un peu que le serveur démarre
    setTimeout(() => loadUrlWithRetry(), 2000);
}

app.whenReady().then(() => {
    startLaravelServer();
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

app.on('will-quit', () => {
    if (laravelProcess) {
        // Sur Windows, taskkill est souvent nécessaire pour tuer l'arbre de processus créé par shell: true
        if (process.platform === 'win32') {
            spawn('taskkill', ['/pid', laravelProcess.pid, '/f', '/t']);
        } else {
            laravelProcess.kill();
        }
    }
});
