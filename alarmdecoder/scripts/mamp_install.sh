# /bin/sh

ROOTDIR=/Applications/MAMP
BINDIR=$ROOTDIR/bin
HTDOCSDIR=$ROOTDIR/htdocs
ALDDIR=$HTDOCSDIR/alarmdecoder
SCRIPTSDIR=$ALDDIR/scripts

# put our startup scripts in place
ln -s $SCRIPTSDIR/egc* $BINDIR

# put our index.php in place
ln -s $ALDDIR/index_alarm.php $HTDOCSDIR/index.php
#
# install our .htaccess to force everything to go to our index.php
ln -s $SCRIPTSDIR/htaccess $HTDOCSDIR/.htaccess

# provide a link to the logs
ln -s $ALDDIR/logs $ROOTDIR/logs/alarmdecoder

# create and install certs
./create_cert.sh

ln -s $SCRIPTSDIR/server.crt $ROOTDIR/conf/apache
ln -s $SCRIPTSDIR/server.key $ROOTDIR/conf/apache
