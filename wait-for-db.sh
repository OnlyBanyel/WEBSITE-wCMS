#!/bin/bash
set -e

# Function to check if MySQL is ready
function mysql_ready() {
    mysqladmin ping -h "$DB_HOST" -u "$DB_USER" --silent
}

# Maximum number of attempts
max_attempts=30
# Delay between attempts in seconds
delay=2

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
attempt=0
while [ $attempt -lt $max_attempts ]; do
    if mysql_ready; then
        echo "MySQL is ready!"
        break
    fi
    
    attempt=$((attempt+1))
    echo "MySQL not ready yet (attempt $attempt/$max_attempts)... waiting ${delay}s"
    sleep $delay
done

if [ $attempt -eq $max_attempts ]; then
    echo "MySQL did not become ready in time."
    exit 1
fi

# Start Apache in foreground
exec "$@"