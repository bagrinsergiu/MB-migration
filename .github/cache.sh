#!/usr/bin/env bash

CACHE_HTTP_API=$1
KEY="$2.tar.gz"
CACHE_HIT=$(curl -X GET $CACHE_HTTP_API/assets/$KEY --silent --output /dev/null --write-out "%{http_code}")

if [[ "${CACHE_HIT}" == "200" ]]; then
  cd ../
  curl -X GET $CACHE_HTTP_API/assets/$KEY --output $KEY
  bash ./.github/get_cache.sh $KEY
else
  echo "Cache miss, downloading dependencies..."
  # go to root
  cd ../

  # npm install
  npm install

  # Create zip with all deps
  echo "Creating zip..."
  bash ./.github/set_cache.sh $KEY

  echo "Caching dependencies...";
  curl -X POST --form file=@$KEY $CACHE_HTTP_API/upload
fi
