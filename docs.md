# Further Setup
(Nginx with PHP, Symlinks, Permissions)  

## PHP for Nginx
```sh
sudo apt install php5-fpm
```

[Enable PHP for Nginx](http://askubuntu.com/questions/134666/what-is-the-easiest-way-to-enable-php-on-nginx) and [Enable symlinks](http://unix.stackexchange.com/questions/157022/make-nginx-follow-symlinks) for Nginx  
```sh
sudo nano /etc/nginx/sites-available/default
```

```sh
sudo service php5-fpm restart
sudo service nginx restart
```
## Symbolic links
```sh
sudo ln -s ~/git/photo photo
sudo ln -s ~/git/photo-gal photo-gal
```
## Permissions
Permissions for `www-data`  
```sh
sudo chown www-data:www-data -R photo
sudo chown www-data:www-data -R photo-gal
sudo chmod 775 -R photo
sudo chmod 775 -R photo-gal
```

Allow gphoto2 to be used by `www-data`
```sh
sudo chmod +s $(which gphoto2)
```
## SCP (upload to server)
Upload albums without a password (mandatory for UI uploads)
```sh
# login as root
sudo su
su - www-data -s /bin/bash -c "ssh-keygen -t rsa"
su - www-data -s /bin/bash -c "ssh-copy-id user@your_website.com -p 22"
# copy ssh directory of user that server already trusts
sudo cp /home/trusted_user/.ssh/* /var/www/.ssh/
```
# Optional

[Https](http://nginx.org/en/docs/http/configuring_https_servers.html) for Nginx   

Disable root login  
```sh
sudo nano /etc/ssh/sshd_config
```

Admin user setup
```sh
sudo su
usermod -l quince pi
usermod -m -d /home/quince quince
chown quince:quince /home/quince
# add user to sudoers file
visudo
groupmod -n quince pi
chown quince:quince /home/quince
cp /etc/skel/.* .
```

Here are some lines from my Nginx config that show where log files are
```sh
#        access_log /var/log/nginx/access.log;
#        error_log /var/log/nginx/error.log;
```

### Additional information
Photos  
(The setup does this for you if you let it)  
Place photos in a sibling directory of the project named `photo` (can be symbolic link).

Zips  
Make a directory named `zips` that is also a sibling directory to the project. Ensure `www-data` has rights to read and write contents.
