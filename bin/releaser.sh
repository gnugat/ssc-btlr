#!/usr/bin/env bash

##
# Script inspired by Matthieu Moquet
#
# @see https://github.com/MattKetmo/cliph/blob/5897c317f2a6a54208482540ef33d7316407a984/bump-version.sh
##

if [ $# -ne 1 ]; then
  echo "Usage: releaser <version>"
  exit 65
fi

ROOT="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
VERSION=$1

# Git release
git checkout master
git tag -a ${TAG}

# Phar release
git checkout gh-pages
./vendor/bin/box build
mv fossil.phar downloads/fossil-${TAG}.phar
git add downloads/fossil-${TAG}.phar

SHA1=$(openssl sha1 fossil.phar)

JSON='name:"fossil.phar"'
JSON="${JSON},sha1:\"${SHA1}\""
JSON="${JSON},url:\"http://gnugat.github.io/fossil/downloads/fossil-${TAG}.phar\""
JSON="${JSON},version:\"${TAG}\""

# Manifest release
cat manifest.json | jsawk -a "this.push({${JSON}})" | python -mjson.tool > manifest.json.tmp
mv manifest.json.tmp manifest.json
git add manifest.json

git commit -m "Released version ${TAG}"

git checkout master
echo "New version created. Now you should run:"
echo "git push origin gh-pages"
echo "git push ${TAG}"
