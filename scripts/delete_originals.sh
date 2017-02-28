if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
photo_dir='../../photo'
if [ ! -d $photo_dir/$1/.web ]; then
  echo "ERROR: $1 webs do not exist"
  exit 1;
fi
echo $(date) Linking webs for $1  and overwriting originals $2 >> log.txt

cd $photo_dir/$1/.web

for f in *.webp; do
  rm ../${f%.*}*
  ln $f ../$f
done
