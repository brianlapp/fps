#!/bin/bash

# This script imports a database dump into your local MySQL database

# Configuration - replace these with your actual values
LOCAL_DB_NAME="freeparentsearch"
LOCAL_DB_USER="root"
DUMP_FILE="database_dump.sql"

# Check if the dump file exists
if [ ! -f "$DUMP_FILE" ]; then
    echo "Error: Database dump file not found: $DUMP_FILE"
    exit 1
fi

# Create the database if it doesn't exist
echo "Creating database if it doesn't exist..."
mysql -u $LOCAL_DB_USER -p -e "CREATE DATABASE IF NOT EXISTS $LOCAL_DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import the database dump
echo "Importing database dump into $LOCAL_DB_NAME..."
mysql -u $LOCAL_DB_USER -p $LOCAL_DB_NAME < $DUMP_FILE

echo "Database import completed successfully!"
