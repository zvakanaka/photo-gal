# photo-gal
Photo manager and gallery made of Vanilla JS, Bash scripts, and PHP

# DSLR 🔌 Raspberry-Pi 📡 Web-Server
![Screenshot](https://github.com/zvakanaka/photo-gal/raw/img/photo-gal.png)  
## Usage
1. Plug DSLR into Raspberry-Pi (or any computer with this set up)
2. Open web browser and go to the ip-address/photo-gal
3. Log into an admin account (after making a user an admin in the setup)
4. Use the UI to download photos from DSLR (creates thumbs and lightbox-sized images too)
5. Optionally upload galleries to a server

## Setup
```sh
$ bash setup.sh
```  

[Further Setup](docs.md)

---
## Local Development
[Sng](https://www.npmjs.com/package/sng) can be used to serve PHP from somewhere in your home folder. Nginx, PHP,  and MySQL are required. Sng requires npm, the neatest way to install that is with [nvm](nvm.sh) (Node Version Manager).

1. Place a file named `.sng.conf` in the parent directory of the project. Place these contents in `.sng.conf`:  
```
# pass the PHP scripts to FastCGI server listening on the php-fpm socket
location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
}
```

2. Run `sng` from that parent directory.

3. Upload albums without a password (mandatory for UI uploads)
```sh
$ ssh-keygen
$ ssh-copy-id user@your_website.com -P 22
```
