#!/bin/bash
cat banner.txt
echo

if [ ! -f setup.sh ]; then
  echo "Setup must be executed from within project directory"
  exit 1
fi
project_dir=$(basename $(pwd))

create_sql_setup () {
  echo "CREATE USER 'photo'@'localhost' IDENTIFIED BY 'PASSWORD_HERE';
CREATE DATABASE photo_db;
-- allow some CRUD to happen
GRANT SELECT, DELETE, INSERT, UPDATE ON photo_db.* TO 'photo'@'localhost';
FLUSH PRIVILEGES;" > model/setup.sql
}

if [ ! -f config.php ]; then
  if [ $(dpkg&>/dev/null) ]; then
    echo "This is not a Debian based OS, please configure dependencies manually"
  else
    echo "Make sure these are installed:"
    echo -e '\n\t sudo apt install mysql-common mysql-server php-common php-mysql webp gphoto2 rsync zip\n'
    echo "If you are using apache, install these too:"
    echo -e '\n\t sudo apt install apache2 libapache2-mod-php\n'
  fi

  if [ $(mysql --version&>/dev/null) ]; then
    echo "The next steps require MySQL be installed, please install it and try again"
    exit 3
  fi

  photo_dir="../photo"
  echo -n "DB password: "
  read -s db_password
  if [ -z $db_password ]; then
    echo -e '\nEmpty db password not allowed'
    exit 2
  fi
  echo -e "<?php
return (object) array(
  'photo_dir' => '$photo_dir',
  'project_dir' => '/$project_dir',
  'db_username' => 'photo',
  'db_password' => '$db_password',
  'db_dsn' => 'mysql:host=localhost;dbname=photo_db'
);
?>" > config.php
  echo "Created default configuration file"

  rm model/setup.sql
  create_sql_setup
  sed -i "" -e "s/PASSWORD_HERE/$db_password/g" model/setup.sql&>/dev/null
  echo "Replaced db setup script's password"

  echo -n "Would you like to create a database and db user? [y/N]: "
  read create_db_and_user
  if [[ $create_db_and_user =~ [yY](es)* ]]; then
    mysql -u root -p -e "source model/setup.sql"
  fi
  echo -n "Would you like to (re)create the photo db? [y/N]: "
  read recreate_db
  if [[ $recreate_db =~ [yY](es)* ]]; then
    mysql -u root -p -e "source model/photo-gal.sql"
  fi

  if [ ! -d $photo_dir ]; then
    echo "No directory named '$photo_dir' exists... (where photos will be stored)"
    echo -n "Would you like to create one? [y/N]: "
    read should_create_photo_dir
    if [[ $should_create_photo_dir =~ [yY](es)* ]]; then
      mkdir $photo_dir
      if [ $? -ne 0 ]; then
        echo "Failed creating directory '$photo_dir', do you have sufficient rights?"
      fi
    fi
  fi
else
  echo "It looks like you already created a configuration..."
  echo -n "Would you like to reset the settings? [y/N]: "
  read start_over
  if [[ $start_over =~ [yY](es)* ]]; then
    rm ./config.php
    create_sql_setup
    echo "Reset complete. Re-run to create a new configuration."
  else
    echo "okay, bye"
  fi
fi
