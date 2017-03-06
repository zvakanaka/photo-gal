if [ -z $1 ]; then
  echo "ERROR: Must supply album name"
  exit 1;
fi
if [ -z $2 ]; then
  echo "ERROR: Must supply server name"
  exit 1;
fi
if [ -z $3 ]; then
  echo "ERROR: Must supply user name"
  exit 1;
fi
if [ -z $5 ]; then
  photo_dir='../photo'
else
  photo_dir=$5
fi
if [ ! -d $photo_dir/$1 ]; then
  echo "ERROR: $1 album does not exist"
  exit 1;
fi
port=22
if [ ! -z $4 ]; then
  port=$4
fi
remote_path="/home/$3/photo"
if [ ! -z $6 ]; then
  remote_path=$6
fi

echo $(date) Uploading $1 to $3@$2 >> log.txt
#options="--chown=www-data:www-data"
rsync -av -e "ssh -p $port" $options --exclude 'raw' --exclude '*.MOV' $photo_dir/$1 $3@$2:$remote_path/
