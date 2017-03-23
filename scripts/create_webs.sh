# $1 album, $2 photo_dir
if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
if [ -z $2 ]; then
  photo_dir='../photo'
else
  photo_dir=$2
fi
echo $(date) Creating webs for $1 from $3 >> scripts/log.txt

cd $photo_dir/$1 && mkdir .web;
for f in *.[jJ]*; do
  cwebp $f -resize 0 1024 -q 70 -short -o .web/${f%.*}.webp;
done
