#!/bin/bash

FILE=$1
tmp=/tmp/tmp$$

cat HEADER <(sed  -e '/<?php/,/*\//d' $FILE) > $tmp-hoge
mv $tmp-hoge $FILE

rm $tmp-*
