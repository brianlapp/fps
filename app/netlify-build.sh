#!/bin/bash

# Netlify build script for Laravel frontend
echo "Starting Netlify build process..."

# Install dependencies
npm ci

# Build frontend assets
npm run build

# Copy necessary files to the publish directory
cp -r public/* public/

# Create a simple index.html if it doesn't exist
if [ ! -f public/index.html ]; then
  cp resources/views/app.blade.php public/index.html
fi

echo "Build completed successfully!"
