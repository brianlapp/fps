#!/bin/bash

# UI Showcase Deployment Script
echo "Starting deployment of Free Parent Search UI Showcase"

# Install dependencies
echo "Installing dependencies..."
npm install

# Build the project
echo "Building the UI showcase..."
npm run build

# Success message
echo "Build completed successfully!"
echo "Your UI showcase is ready to be deployed to Netlify."
echo "Push your changes to GitHub and Netlify will automatically deploy your UI showcase."
