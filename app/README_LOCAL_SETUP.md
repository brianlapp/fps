# Local Development Setup Guide

## Option 1: Using a Database Dump (Recommended for UI Work)

This approach gives you a complete local development environment where you can safely make both UI and database changes.

### Prerequisites

- MySQL installed locally
- Access to your Google Cloud SQL instance
- Google Cloud SDK installed (for the database dump)

### Step 1: Configure Database Connection

Update your `.env` file to use MySQL instead of SQLite:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=freeparentsearch
DB_USERNAME=your_local_username
DB_PASSWORD=your_local_password
```

### Step 2: Dump the Production Database

1. Edit the `dump_database.sh` script with your Google Cloud SQL instance details
2. Make the script executable: `chmod +x dump_database.sh`
3. Run the script: `./dump_database.sh`

### Step 3: Import the Database Dump

1. Edit the `import_database.sh` script with your local MySQL details
2. Make the script executable: `chmod +x import_database.sh`
3. Run the script: `./import_database.sh`

### Step 4: Run the Application

```bash
php artisan serve
```

## Option 2: Connect to Production Database (Quick Setup)

This approach is faster to set up but limits your ability to make database changes.

### Step 1: Configure Remote Database Connection

Update your `.env` file with the production database credentials:

```
DB_CONNECTION=mysql
DB_HOST=your_cloud_sql_instance_ip
DB_PORT=3306
DB_DATABASE=your_production_db_name
DB_USERNAME=your_production_db_username
DB_PASSWORD=your_production_db_password
```

### Step 2: Set Up Cloud SQL Proxy (Recommended)

For secure access to your Cloud SQL instance:

```bash
# Install Cloud SQL Proxy
gcloud components install cloud_sql_proxy

# Start the proxy
cloud_sql_proxy -instances=your-project:your-region:your-instance=tcp:3306
```

### Step 3: Run the Application

```bash
php artisan serve
```

## Important Notes

1. When connected to the production database, avoid running migrations or making schema changes
2. For UI work, the database dump approach (Option 1) is safer and more flexible
3. Consider creating a read-only user for Option 2 to prevent accidental data modification
4. Always back up your production database before making any changes
