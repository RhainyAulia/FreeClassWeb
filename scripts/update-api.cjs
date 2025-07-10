const fs = require('fs');
const path = require('path');
const axios = require('axios');

async function main() {
  try {
    // Ambil URL dari ngrok local API
    const res = await axios.get('http://127.0.0.1:4040/api/tunnels');
    const tunnel = res.data.tunnels.find(t => t.proto === 'https');
    const url = tunnel.public_url;

    if (!url) {
      console.error('❌ Gagal menemukan URL ngrok.');
      return;
    }

    // Update .env
    const envPath = path.resolve(__dirname, '../.env');
    let envContent = fs.readFileSync(envPath, 'utf8');
    envContent = envContent.replace(/APP_URL=.*/g, `APP_URL=${url}`);
    fs.writeFileSync(envPath, envContent);
    console.log(`✅ APP_URL di .env diubah ke ${url}`);

    // Update api_url.json
    const apiUrlJson = { url: `${url}/api` };
    fs.writeFileSync(path.resolve(__dirname, '../public/api_url.json'), JSON.stringify(apiUrlJson, null, 2));
    console.log('✅ public/api_url.json berhasil dibuat.');

    console.log('✅ Semua selesai! Silakan restart Laravel.');
  } catch (err) {
    console.error('❌ Gagal update URL:', err.message);
  }
}

main();
