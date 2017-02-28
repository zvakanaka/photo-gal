if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
photo_dir='../../photo'
echo $(date) Creating thumbs for $1 from $2 >> log.txt

cd $photo_dir/$1
if [ $? -eq 0 ]; then
  if [ ! -d .thumb ]; then
    echo "$1 thumbs does not exist, creating..."
    mkdir .thumb
  fi
  if [ $? -eq 0 ]; then
    for f in *.[jJ]*; do
      cwebp $f -resize 0 120 -q 50 -short -o .thumb/${f%.*}.webp;
    done
  else
    echo $(date) Failure creating thumbs: user: $(echo $USER) >> log.txt
  fi
fi
