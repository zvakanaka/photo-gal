if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
if [ -z $2 ]; then
  echo "ERROR: Must supply photo name"
  exit 1;
fi
if [ -z $3 ]; then
  photo_dir='../photo'
else
  photo_dir=$3
fi
if [ ! -f $photo_dir/$1/$2 ]; then
  echo "$photo_dir/$1/$2 does not exist"
  exit 1
fi
echo $(date) Deleteing $photo_dir/$1/$2 from $4 >> log.txt
rm $photo_dir/$1/$2 $photo_dir/$1/{.thumb,.web}/${2%.*}.* $photo_dir/$1/${2%.*}.NEF
