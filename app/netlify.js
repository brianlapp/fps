// Netlify build helper script
console.log('Starting Netlify build process...');

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

// Get the directory name in ESM
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Ensure the public directory exists
const publicDir = path.join(__dirname, 'public');
if (!fs.existsSync(publicDir)) {
  fs.mkdirSync(publicDir, { recursive: true });
}

// Create 200.html and 404.html from index.html if they don't exist
const indexPath = path.join(publicDir, 'index.html');
const page200Path = path.join(publicDir, '200.html');
const page404Path = path.join(publicDir, '404.html');

if (fs.existsSync(indexPath)) {
  const indexContent = fs.readFileSync(indexPath, 'utf8');
  fs.writeFileSync(page200Path, indexContent);
  fs.writeFileSync(page404Path, indexContent);
  console.log('Created 200.html and 404.html from index.html');
} else {
  console.log('Warning: index.html not found in public directory');
}

console.log('Netlify build process completed!');
