if [ -z $1 ]; then
  echo "ERROR: Must supply album name"
  exit 1;
fi
if [ -z $2 ]; then
  photo_dir='../photo'
else
  photo_dir=$2
fi
if [ -d $photo_dir/$1 ]; then
  rmdir $photo_dir/$1
  pwd
  if [ $? ]; then
    echo "ERROR: $1 album already exists and is not empty"
    exit 1;
  fi
fi
echo $(date) Downloading from DSLR to $1 >> scripts/log.txt

mkdir $photo_dir/$1 && cd $photo_dir/$1;
gphoto2 --get-all-files

bash create_thumbs.sh $1 $photo_dir
bash create_webs.sh $1 $photo_dir
