#!/bin/bash

if [ $EUID -ne 0 ];
then
	echo "Failure: you should run this script as root."
	exit 1
fi

apt-get install php5-cli php5-curl
CLIENT_DIR="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
if [ ! -e /usr/bin/rk ];
then
	ln -s "$CLIENT_DIR/roboticks" /usr/bin/rk
fi
PRESENCE="$(grep -c robot /etc/bash.bashrc)"
if [ $PRESENCE -eq "0" ];
then
	cat "$CLIENT_DIR/functions" >> /etc/bash.bashrc
fi
source "$CLIENT_DIR/functions"
echo "Success: installed roboticks client."
