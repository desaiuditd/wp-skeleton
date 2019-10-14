#!/bin/bash

wget https://github.com/desaiuditd/wp-skeleton/archive/master.zip
unzip master.zip
shopt -s dotglob
mv wp-skeleton-master/* ./
rm -rf wp-skeleton-master
rm -rf master.zip
rm -rf bin
