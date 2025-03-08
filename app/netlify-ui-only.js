// Netlify UI-only build script
// This script creates a simplified version of the app for Netlify deployment
// focusing only on UI components without backend dependencies

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

console.log('Starting UI-only build for Netlify deployment...');

// Ensure the public directory exists
const publicDir = path.join(__dirname, 'public');
if (!fs.existsSync(publicDir)) {
  fs.mkdirSync(publicDir, { recursive: true });
}

// Create a simplified index.html for UI preview
const indexHtml = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Parent Search - UI Preview</title>
    <link rel="stylesheet" href="/build/assets/app-BIN-f_HR.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .hero {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .hero h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 1.5rem;
        }
        .search-box {
            display: flex;
            margin-bottom: 1.5rem;
        }
        .search-box input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 1rem;
        }
        .search-box button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-size: 1rem;
        }
        .feature-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .feature-link {
            background-color: #ecf0f1;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .feature-link i {
            color: #3498db;
        }
        .grid-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        .card p {
            color: #7f8c8d;
            margin-bottom: 1rem;
        }
        .card a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            text-decoration: none;
        }
        .navbar-links {
            display: flex;
            gap: 1.5rem;
        }
        .navbar-links a {
            color: #7f8c8d;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .navbar-links a:hover {
            color: #3498db;
        }
        .footer {
            margin-top: 3rem;
            padding: 2rem;
            background-color: #2c3e50;
            color: white;
            text-align: center;
        }
        .ui-notice {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="ui-notice">
        <strong>UI Preview Only:</strong> This is a static preview of the UI. Backend functionality is not available in this deployment.
    </div>
    
    <nav class="navbar">
        <a href="#" class="navbar-brand">Free Parent Search</a>
        <div class="navbar-links">
            <a href="#">Home</a>
            <a href="#">Resources</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <h1>Find the Resources You Need</h1>
            <p>Connecting parents with free and low-cost resources in your community</p>
            
            <div class="search-box">
                <input type="text" placeholder="Search for resources...">
                <button><i class="fas fa-search"></i> Search</button>
            </div>
            
            <div class="feature-links">
                <a href="#" class="feature-link"><i class="fas fa-utensils"></i> Food Assistance</a>
                <a href="#" class="feature-link"><i class="fas fa-home"></i> Housing</a>
                <a href="#" class="feature-link"><i class="fas fa-book"></i> Education</a>
                <a href="#" class="feature-link"><i class="fas fa-heartbeat"></i> Healthcare</a>
                <a href="#" class="feature-link"><i class="fas fa-child"></i> Childcare</a>
            </div>
        </div>
        
        <div class="grid-section">
            <div class="card">
                <h3>Food Assistance Programs</h3>
                <p>Find free and reduced-cost meal programs, food pantries, and nutrition assistance in your area.</p>
                <a href="#">Learn More →</a>
            </div>
            
            <div class="card">
                <h3>Housing Resources</h3>
                <p>Discover affordable housing options, rental assistance programs, and emergency shelter services.</p>
                <a href="#">Learn More →</a>
            </div>
            
            <div class="card">
                <h3>Education Support</h3>
                <p>Access free educational resources, tutoring services, and school supplies for your children.</p>
                <a href="#">Learn More →</a>
            </div>
            
            <div class="card">
                <h3>Healthcare Services</h3>
                <p>Connect with free and low-cost medical care, mental health services, and wellness programs.</p>
                <a href="#">Learn More →</a>
            </div>
            
            <div class="card">
                <h3>Childcare Options</h3>
                <p>Find affordable childcare providers, after-school programs, and early childhood education resources.</p>
                <a href="#">Learn More →</a>
            </div>
            
            <div class="card">
                <h3>Financial Assistance</h3>
                <p>Learn about financial aid programs, budgeting resources, and emergency financial assistance.</p>
                <a href="#">Learn More →</a>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <p>© 2025 Free Parent Search. All rights reserved.</p>
    </footer>
</body>
</html>`;

// Write the simplified index.html file
fs.writeFileSync(path.join(publicDir, 'index.html'), indexHtml);
fs.writeFileSync(path.join(publicDir, '200.html'), indexHtml);
fs.writeFileSync(path.join(publicDir, '404.html'), indexHtml);

// Create a simple API placeholder response
const apiPlaceholder = {
  message: "This is a static deployment of the Free Parent Search frontend. API endpoints are not available in this preview version.",
  status: "success",
  data: {
    note: "This is placeholder data for UI development and testing purposes.",
    timestamp: new Date().toISOString()
  }
};

fs.writeFileSync(
  path.join(publicDir, 'api-placeholder.json'), 
  JSON.stringify(apiPlaceholder, null, 2)
);

console.log('UI-only build for Netlify deployment completed successfully!');
console.log('Created index.html, 200.html, 404.html, and api-placeholder.json');
