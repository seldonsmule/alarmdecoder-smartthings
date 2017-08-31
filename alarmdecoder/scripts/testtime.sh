BOOTTIME=`sysctl -n kern.boottime |awk '{print $4}'`
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
fi
