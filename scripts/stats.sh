#!/bin/bash

# get camera model
a=$(gphoto2 --summary | grep 'Model:')
camera_model=${a#*: } # strip beginning

# get file-counts
output=$(gphoto2 --list-files)
num_images=$(echo $output | grep 'image/' -o | wc -l)
num_videos=$(echo $output | grep 'video/\|application/x-unknown' -o | wc -l)

# echo Your $camera_model has $num_images images and $num_videos videos.

if [ $1 == 'images' ]; then
  echo $num_images
elif [ $1 == 'videos' ]; then
  echo $num_videos
elif [ $1 == 'model' ]; then
  echo $camera_model
fi
