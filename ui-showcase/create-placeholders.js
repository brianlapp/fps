const fs = require('fs');
const path = require('path');

// Create necessary directories
const directories = [
  'public/images',
  'public/images/categories',
  'public/images/icons'
];

directories.forEach(dir => {
  const fullPath = path.join(__dirname, dir);
  if (!fs.existsSync(fullPath)) {
    fs.mkdirSync(fullPath, { recursive: true });
    console.log(`Created directory: ${fullPath}`);
  }
});

// Create a simple placeholder SVG
function createPlaceholderSVG(width, height, text, bgColor = '#4F46E5') {
  return `<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
    <rect width="${width}" height="${height}" fill="${bgColor}" />
    <text x="50%" y="50%" font-family="Arial" font-size="20" fill="white" text-anchor="middle" dominant-baseline="middle">${text}</text>
  </svg>`;
}

// List of images to create
const images = [
  { name: 'avatar.png', width: 200, height: 200, text: 'Avatar' },
  { name: 'article_placeholder.png', width: 800, height: 600, text: 'Article Image' },
  { name: 'family.png', width: 800, height: 600, text: 'Family Image' },
  { name: 'banner.png', width: 1200, height: 400, text: 'Banner Image' },
  { name: 'activity_banner.jpg', width: 1200, height: 400, text: 'Activity Banner' },
  { name: 'toddler.png', width: 800, height: 600, text: 'Toddler' },
  { name: 'preschool.png', width: 800, height: 600, text: 'Preschool' },
  { name: 'school.png', width: 800, height: 600, text: 'School Age' },
];

// Create category images
const categories = [
  'new-parents',
  'expecting-parents',
  'paretning-tips',
  'free-samples',
  'sweepstakes',
  'paid-surveys'
];

categories.forEach(category => {
  images.push({
    name: `categories/${category}.png`,
    width: 400,
    height: 300,
    text: category.replace(/-/g, ' '),
    bgColor: '#3B82F6'
  });
});

// Write the SVG files
images.forEach(img => {
  const filePath = path.join(__dirname, 'public/images', img.name);
  const dirPath = path.dirname(filePath);
  
  // Ensure directory exists
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
  }
  
  const svg = createPlaceholderSVG(img.width, img.height, img.text, img.bgColor);
  fs.writeFileSync(filePath, svg);
  console.log(`Created placeholder image: ${filePath}`);
});

console.log('All placeholder images have been created!');
