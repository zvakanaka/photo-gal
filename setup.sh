#!/bin/bash
cat banner-color.txt
echo

if [ ! -f setup.sh ]; then
  echo "ERROR: Setup must be executed from within project directory"
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
    echo "WARNING: This does not seem to be a Debian based OS, please configure dependencies manually"
  else
    echo "Make sure these are installed:"
    echo -e '\n\t sudo apt install mysql-common mysql-server php5-common php5-mysql webp imagemagick gphoto2 rsync zip\n'
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

  echo -n "MySQL root password: "
  read -s root_db_password
  if [ -z $root_db_password ]; then
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
    echo "Executing command as MySQL root user"
    mysql -u root -p$root_db_password -e "source model/setup.sql"
  fi
  echo -n "Would you like to (re)create the photo db? [y/N]: "
  read recreate_db
  if [[ $recreate_db =~ [yY](es)* ]]; then
    echo "Executing command as MySQL root user"
    mysql -u root -p$root_db_password -e "source model/photo-gal.sql"
  fi

  if [[ $? -eq 0 ]]; then
    echo "Successfully set up database."
    echo "If you would like to do the next step, go create a normal user first (from a browser)"
    HOSTNAME=$(hostname -I)
    echo -e "\thttp://$HOSTNAME/photo-gal"
  fi
  echo
  echo -n "Would you like to make a user an admin? [y/N]: "
  read create_db_admin
  if [[ $create_db_admin =~ [yY](es)* ]]; then
    echo -n "Existing username that shall be made an admin? : "
    read username
    echo "Executing command as MySQL root user"
    mysql -uroot -p$root_db_password -e "UPDATE users SET is_admin = 1 WHERE user_id = (SELECT user_d FROM users WHERE username = '$username');"
  fi

  if [ ! -d $photo_dir ]; then
    echo "No directory named '$photo_dir' exists... (where photos will be stored)"
    echo -n "Would you like to create one? [y/N]: "
    read should_create_photo_dir
    if [[ $should_create_photo_dir =~ [yY](es)* ]]; then
      mkdir $photo_dir
      if [ $? -ne 0 ]; then
        echo "Failed creating directory '$photo_dir', do you have sufficient rights?"
      else
        echo "Directory created in $photo_dir"
      fi
    fi
  fi
  echo "Setup script complete!"
  echo "Now read docs.md for further setup (Nginx, PHP, SSH)"
else
  echo "It looks like you already created a configuration..."
  echo -n "Would you like to reset the settings? [y/N]: "
  read start_over
  if [[ $start_over =~ [yY](es)* ]]; then
    rm ./config.php
    create_sql_setup
    echo "Reset complete. Re-running to create a new configuration..."
    bash setup.sh
  else
    echo "okay, bye"
  fi
fi
