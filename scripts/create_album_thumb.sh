if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
photo_dir='../../photo'
if [ ! -d $photo_dir/$1/.album ]; then
  echo "Creating album thumb dir for $1"
  mkdir $photo_dir/$1/.album
fi
echo $(date) Creating album thumb for $1: $2 from $3 >> log.txt
cp $photo_dir/$1/.thumb/${2%.*}.webp $photo_dir/$1/.album/thumb.webp
cwebp $photo_dir/$1/.album/thumb.webp -crop 45 0 120 120 -short -o $photo_dir/$1/.thumb/icon.webp
