if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
photo_dir='../../photo'
echo $(date) Creating webs for $1 from $2 >> log.txt

cd $photo_dir/$1 && mkdir .web;
for f in *.[jJ]*; do
  cwebp $f -resize 0 1024 -q 70 -short -o .web/${f%.*}.webp;
done
