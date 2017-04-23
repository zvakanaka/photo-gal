if [ -z $1 ]; then
  photo_dir='../photo'
else
  photo_dir=$1
fi
function process() {
  cd $1
  first_photo=$(ls *JPG | sort -n | head -1)
  cd -
  bash scripts/create_thumbs.sh $1 $photo_dir
  bash scripts/create_webs.sh $1 $photo_dir
  bash scripts/create_album_thumb.sh $1 $first_photo $photo_dir
}
echo $(date) Creating thumbs and webs for all albums >> scripts/log.txt
if [ -d $photo_dir ]; then
  for f in $photo_dir/*;
  do
     [ -d $f ] && process "$f" && echo Generating thumbs and webs for $f

  done;
else
  echo $(date) Error: photo directory, $photo_dir does not exist >> scripts/log.txt
fi
