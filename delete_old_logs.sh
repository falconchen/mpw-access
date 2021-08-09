#!/usr/bin/env bash

#获取当前目录
SCRIPT_DIR=$(dirname "$0")
CONFIG=$SCRIPT_DIR/config.sh

if [ ! -f $CONFIG ];then
    echo ${CONFIG} not exist!
    exit 2
fi
source $CONFIG

find ${ACCESS_LOG_DIR} -type f  -mtime +${EXPIRE_LOG_DAYS} | xargs rm -rf


