const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

function extractNgrokUrl(output) {
  const match = output.match(/url=(https:\/\/[a-zA-Z0-9-]+\.ngrok-free\.app)/);
  return match ? match[1] : null;
}

async function main() {
  console.log('Menjalankan ngrok di port 8000...');
  const ngrokOutput = execSync('npx ngrok http 8000 --log=stdout').toString();
  const url = extractNgrokUrl(ngrokOutput);

  if (!url) {
    console.error('Gagal mengambil URL ngrok.');
    return;
  }

  const envPath = path.resolve(__dirname, '../.env');
  let envContent = fs.readFileSync(envPath, 'utf8');
  envContent = envContent.replace(/APP_URL=.*/g, `APP_URL=${url}`);
  fs.writeFileSync(envPath, envContent);
  console.log(`✅ APP_URL di .env diubah ke ${url}`);

  const apiUrlJson = { url: `${url}/api` };
  fs.writeFileSync(path.resolve(__dirname, '../public/api_url.json'), JSON.stringify(apiUrlJson, null, 2));
  console.log('✅ File public/api_url.json berhasil dibuat.');

  console.log('Selesai. Silakan restart server Laravel.');
}

main();
