if [ -z $1 ]; then
  echo "ERROR: Must supply album name"
  exit 1;
fi
photo_dir='../../photo'
if [ -d $photo_dir/$1 ]; then
  echo "ERROR: $1 album already exists"
  exit 1;
fi
echo $(date) Downloading from DSLR to $1 >> log.txt

mkdir $photo_dir/$1 && cd $photo_dir/$1;
gphoto2 --get-all-files
