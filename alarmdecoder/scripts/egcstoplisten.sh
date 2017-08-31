ROOTPATH="/Applications/MAMP/htdocs/alarmdecoder"
SCRIPTPATH="$ROOTPATH/scripts"

. $SCRIPTPATH/env_profile.sh

PSOUT=`ps -ef|grep listen.php|grep -v grep`

if [ "$?" = "0" ]
then 
  #echo $PSOUT
  PID=`echo $PSOUT |awk '{print $2}'`
  #echo pid = $PID
  kill $PID
  echo "listen.php stopped"
  exit 0
fi

echo "listen.php not running"
