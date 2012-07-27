#!/bin/bash

DIR=`pwd | grep 'calypso-website$'`

if [ -z "$DIR" ]; then
  echo "Not in right directory!"
  exit
fi

echo -n "Enter sunetid: "

read sunetid

rsync -trz --exclude 'deploy.sh' --exclude '*~' --exclude '.git/' --exclude '.gitignore' --exclude '.DS_STORE' --exclude 'README.md' --exclude '*.mp3' --exclude '*.jpg' --exclude '*.png' --exclude '*.pdf' --exclude '*.midi' --exclude '*.mid' . $sunetid@myth.stanford.edu:/afs/ir.stanford.edu/group/calypso/cgi-bin/

echo "Completed"