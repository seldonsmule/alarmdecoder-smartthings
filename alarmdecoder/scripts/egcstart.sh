# /bin/sh

# first check to see if 5 min has passed since the system was booted (300 seconds) before starting.  I found some timeing
# issues if not.

BOOTTIME=`/usr/sbin/sysctl -n kern.boottime |awk '{print $4}'`
BOOTTIME=`echo ${BOOTTIME%?}`  # internet is wonderfull.  
                               # this strips off the last char
                               # which was a ','

TIMENOW=`date +"%s"`

TIMEDIFF=`expr $TIMENOW - $BOOTTIME`

BOOTWAIT=300  # we want to wait 5 minutes before allowing

if [ "$TIMEDIFF" -gt "$BOOTWAIT" ] 
then
  echo "$TIMEDIFF secs have passed since boot, we only needed $BOOTWAIT"
else
  echo "Still waiting.  $TIMEDIFF has passed need $BOOTWAIT"
  exit 0
fi

ROOTDIR=/Applications/MAMP
HTTPPIDFILE=$ROOTDIR/Library/logs/httpd.pid
MYSQLPIDFILE=$ROOTDIR/tmp/mysql/mysql.pid

if ! test -f $HTTPPIDFILE
then
  $ROOTDIR/bin/startApache.sh
else
  PID=`cat $HTTPPIDFILE`
  ps -p $PID 2>&1 >> /dev/null
  if [ "$?" != "0" ]
  then
    echo starting
    $ROOTDIR/bin/startApache.sh
  else
    echo Apache already running
  fi
fi

if ! test -f $MYSQLPIDFILE
then
  $ROOTDIR/bin/egcstartMysql.sh
fi

# lastly - see if our listener is running

$ROOTDIR/bin/egcstartlisten.sh

#/Applications/MAMP/bin/startMysql.sh
#/Applications/MAMP/bin/startApache.sh
