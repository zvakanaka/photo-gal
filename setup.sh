#!/bin/bash
echo "PHOTO-GAL SETUP"
echo
if [ ! -f config.php ]; then
  echo -n "DB password: "
  read -s db_password
echo -e "<?php
return (object) array(
  'photo_dir' => '../photo',
  'project_dir' => '/photo-gal',
  'db_username' => 'photo',
  'db_password' => '$db_password',
  'db_dsn' => 'mysql:host=localhost;dbname=photo_db'
);
?>" > config.php
  echo "Created default configuration file"

  echo "Replacing db setup script's password..."
  sed -ie 's/PASSWORD_HERE/$db_password/g' model/setup.sql

  echo -n "Would you like to create a database and db user? (y,n): "
  read create_db_and_user
  if [ $create_db_and_user == 'y' ]; then
    mysql -u photo -p$db_password -e "source model/setup.sql"
  fi
  echo -n "Would you like to (re)create the photo db? (y,n): "
  read recreate_db
  if [ $recreate_db == 'y' ]; then
    mysql -u photo -p$db_password -e "source model/photo-gal.sql"
  fi
else
  echo "It looks like you already created a configuration..."
  echo -n "Would you like to reset the settings? (y,n): "
  read start_over
  if [ $start_over == 'y' ]; then
    rm ./config.php
    echo -e "CREATE USER 'photo'@'localhost' IDENTIFIED BY 'PASSWORD_HERE';
CREATE DATABASE photo_db;
-- allow some CRUD to happen
GRANT SELECT, DELETE, INSERT, UPDATE ON photo_db.* TO 'photo'@'localhost';
FLUSH PRIVILEGES;" > model/setup.sql
    echo "Reset complete. Re-run to create a new configuration."
  else
    echo "okay, bye"
  fi
fi
