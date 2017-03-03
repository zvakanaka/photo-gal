# photo-gal
Photo management and gallery made of Vanilla JS, Bash scripts, and PHP

# Setup
`$ bash setup.sh`
---
## Local
[Sng](https://www.npmjs.com/package/sng) can be used to serve PHP from somewhere in your home folder. Nginx, PHP,  and MySQL are required. Sng requires npm, the neatest way to install that is with [nvm](nvm.sh) (Node Version Manager).

Place a file named `.sng.conf` in the parent directory of the project. Place these contents in `.sng.conf`:  
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

Then run `sng` from the parent directory.

## Photos
Place photos in a sibling directory of the project named `photo` (can be symbolic link).

## Zips
Make a directory named `zips` that is also a sibling directory to the project. Ensure `www-data` has rights to read and write contents.
