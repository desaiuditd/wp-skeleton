#!/bin/bash

shopt -s dotglob
wget https://github.com/desaiuditd/wp-skeleton/archive/master.zip
unzip master.zip
mv wp-skeleton-master/* .
mv wp-skeleton-master/.* .
rm -rf wp-skeleton-master
rm -rf master.zip
