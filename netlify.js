// Netlify root build script
// This script coordinates the build process for Netlify deployment

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

console.log('Starting Netlify build process from repository root...');

// Ensure we're in the repository root
const repoRoot = __dirname;
const appDir = path.join(repoRoot, 'app');

// Verify directories exist
if (!fs.existsSync(appDir)) {
  console.error('Error: app directory not found!');
  process.exit(1);
}

// Create the public directory if it doesn't exist
const publicDir = path.join(repoRoot, 'public');
if (!fs.existsSync(publicDir)) {
  fs.mkdirSync(publicDir, { recursive: true });
}

try {
  // Create a static HTML file for the UI preview
  console.log('Creating static UI preview...');
  
  const indexHtml = `<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Free Parent Search</title>
        <meta name="description" content="Connecting parents with free and low-cost resources in your community">
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="Free Parent Search">
        <meta property="og:description" content="Connecting parents with free and low-cost resources in your community">
        <meta property="og:image" content="/images/og-image.jpg"/>
        <meta property="og:url" content="https://freeparentsearch.org">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

        <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
        <link rel="shortcut icon" href="/images/icons/favicon.ico">
        <meta name="theme-color" content="#ffffff">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Flowbite CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
        
        <style>
            [data-inertia] {
                transition: opacity 0.5s ease;
            }
            
            .inertia-loading {
                opacity: 0.5;
            }
            
            .ui-notice {
                background-color: #f8d7da;
                color: #721c24;
                padding: 0.75rem;
                border-radius: 4px;
                margin-bottom: 1rem;
                text-align: center;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 9999;
            }
            
            body {
                font-family: 'Figtree', sans-serif;
            }
            
            .hero-unit {
                background-color: #f8f9fa;
                padding: 3rem 1.5rem;
                border-radius: 0.5rem;
                margin-bottom: 2rem;
            }
            
            .hero-headline {
                font-size: 2.5rem;
                font-weight: 600;
                color: #2d3748;
                margin-bottom: 1rem;
            }
            
            .hero-search {
                max-width: 600px;
                margin: 0 auto 1.5rem;
            }
            
            .hero-feature-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
            
            .feature-link {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background-color: #fff;
                border-radius: 0.25rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                color: #4a5568;
                text-decoration: none;
                transition: all 0.2s ease;
            }
            
            .feature-link:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            
            .main-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 1.5rem;
                padding: 1.5rem;
            }
            
            .card {
                background-color: white;
                border-radius: 0.5rem;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 1.5rem;
                transition: transform 0.3s ease;
            }
            
            .card:hover {
                transform: translateY(-5px);
            }
            
            .navbar {
                background-color: white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                padding: 1rem 2rem;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="ui-notice">
            <strong>UI Preview Only:</strong> This is a static preview of the Free Parent Search UI. Backend functionality is not available in this deployment.
        </div>
        
        <div id="app" data-inertia>
            <!-- Navigation -->
            <nav class="navbar flex justify-between items-center">
                <a href="#" class="text-xl font-bold text-gray-800">Free Parent Search</a>
                <div class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-600 hover:text-gray-900">Home</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">Resources</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">About</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">Contact</a>
                </div>
                <button class="md:hidden text-gray-600">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
            
            <!-- Main Content -->
            <main class="container mx-auto px-4 py-8">
                <!-- Hero Unit -->
                <div class="hero-unit text-center">
                    <h1 class="hero-headline">Find the Resources You Need</h1>
                    <p class="text-gray-600 text-lg mb-6">Connecting parents with free and low-cost resources in your community</p>
                    
                    <!-- Hero Search -->
                    <div class="hero-search">
                        <div class="relative">
                            <input type="text" placeholder="Search for resources..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-500 text-white px-4 py-1 rounded-md">
                                <i class="fas fa-search mr-2"></i> Search
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hero Feature Links -->
                    <div class="hero-feature-links">
                        <a href="#" class="feature-link">
                            <i class="fas fa-utensils text-blue-500"></i>
                            <span>Food Assistance</span>
                        </a>
                        <a href="#" class="feature-link">
                            <i class="fas fa-home text-blue-500"></i>
                            <span>Housing</span>
                        </a>
                        <a href="#" class="feature-link">
                            <i class="fas fa-book text-blue-500"></i>
                            <span>Education</span>
                        </a>
                        <a href="#" class="feature-link">
                            <i class="fas fa-heartbeat text-blue-500"></i>
                            <span>Healthcare</span>
                        </a>
                        <a href="#" class="feature-link">
                            <i class="fas fa-child text-blue-500"></i>
                            <span>Childcare</span>
                        </a>
                    </div>
                </div>
                
                <!-- Main Grid -->
                <div class="main-grid">
                    <div class="card">
                        <h3 class="text-xl font-semibold mb-2">Food Assistance Programs</h3>
                        <p class="text-gray-600 mb-4">Find free and reduced-cost meal programs, food pantries, and nutrition assistance in your area.</p>
                        <a href="#" class="text-blue-500 hover:underline">Learn More u2192</a>
                    </div>
                    
                    <div class="card">
                        <h3 class="text-xl font-semibold mb-2">Housing Resources</h3>
                        <p class="text-gray-600 mb-4">Discover affordable housing options, rental assistance programs, and emergency shelter services.</p>
                        <a href="#" class="text-blue-500 hover:underline">Learn More u2192</a>
                    </div>
                    
                    <div class="card">
                        <h3 class="text-xl font-semibold mb-2">Education Support</h3>
                        <p class="text-gray-600 mb-4">Access free educational resources, tutoring services, and school supplies for your children.</p>
                        <a href="#" class="text-blue-500 hover:underline">Learn More u2192</a>
                    </div>
                    
                    <div class="card">
                        <h3 class="text-xl font-semibold mb-2">Healthcare Services</h3>
                        <p class="text-gray-600 mb-4">Connect with free and low-cost medical care, mental health services, and wellness programs.</p>
                        <a href="#" class="text-blue-500 hover:underline">Learn More u2192</a>
                    </div>
                    
                    <div class="card">
                        <h3 class="text-xl font-semibold mb-2">Childcare Options</h3>
                        <p class="text-gray-600 mb-4">Find affordable childcare providers, after-school programs, and early childhood education resources.</p>
                        <a href="#" class="text-blue-500 hover:underline">Learn More u2192</a>
                    </div>
                    
                    <div class="card">
                        <h3 class="text-xl font-semibold mb-2">Financial Assistance</h3>
                        <p class="text-gray-600 mb-4">Learn about financial aid programs, budgeting resources, and emergency financial assistance.</p>
                        <a href="#" class="text-blue-500 hover:underline">Learn More u2192</a>
                    </div>
                </div>
                
                <!-- Static Pages -->
                <div class="mt-12 mb-8 p-6 bg-white rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4">About Free Parent Search</h2>
                    <p class="text-gray-700 mb-4">Free Parent Search is dedicated to connecting parents with free and low-cost resources in their communities. Our mission is to make essential services accessible to all families, regardless of their financial situation.</p>
                    <p class="text-gray-700">We believe that every child deserves access to quality food, housing, education, healthcare, and childcare. By providing a centralized platform for resource discovery, we aim to empower parents to find the support they need for their families.</p>
                </div>
                
                <div class="mb-12 p-6 bg-white rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Contact Us</h2>
                    <p class="text-gray-700 mb-6">Have questions or suggestions? We'd love to hear from you!</p>
                    
                    <form class="space-y-4">
                        <div>
                            <label for="name" class="block text-gray-700 mb-1">Your Name</label>
                            <input type="text" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="message" class="block text-gray-700 mb-1">Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <button type="button" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors">
                            Send Message
                        </button>
                    </form>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-xl font-bold mb-2">Free Parent Search</h3>
                            <p class="text-gray-400">Connecting families with essential resources</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <hr class="border-gray-700 my-6">
                    <div class="text-center text-gray-400">
                        <p>&copy; 2025 Free Parent Search. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
        
        <!-- Flowbite JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    </body>
</html>`;

  // Write the files to the public directory
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
  
  // Copy any static assets if needed
  // This is a simplified approach - you may need to customize this based on your needs
  if (fs.existsSync(path.join(appDir, 'public', 'images'))) {
    // Create the images directory in the public folder
    if (!fs.existsSync(path.join(publicDir, 'images'))) {
      fs.mkdirSync(path.join(publicDir, 'images'), { recursive: true });
    }
    
    // This is a simplified copy - in a real scenario, you'd want to use a proper recursive copy
    console.log('Note: Image copying is simplified in this script. You may need to manually copy images.');
  }
  
  console.log('Static UI preview created successfully!');
  console.log('Files created: index.html, 200.html, 404.html, and api-placeholder.json');
  
} catch (error) {
  console.error('Error during build process:', error);
  process.exit(1);
}

console.log('Netlify build process completed successfully!');
