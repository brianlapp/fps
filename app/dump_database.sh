#!/bin/bash

# This script connects to your Google Cloud SQL instance and dumps the database
# You'll need to have the Google Cloud SDK installed and be authenticated

# Configuration - replace these with your actual values
CLOUD_SQL_INSTANCE="your-instance-name"
DB_NAME="your-database-name"
DB_USER="your-database-user"
OUTPUT_FILE="database_dump.sql"

# Create a Cloud SQL Proxy connection
echo "Setting up Cloud SQL Proxy connection..."
gcloud sql connect $CLOUD_SQL_INSTANCE --user=$DB_USER

# Dump the database
echo "Dumping database to $OUTPUT_FILE..."
mysqldump -u $DB_USER -p $DB_NAME > $OUTPUT_FILE

echo "Database dump completed. The file is located at: $OUTPUT_FILE"
