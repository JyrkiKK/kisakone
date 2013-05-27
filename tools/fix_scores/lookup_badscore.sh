#!/bin/bash

PLAYERID="$1"
ROUNDID="$2"
FILE="/tmp/lookup.sql"

[ ! $# -eq 2 ] && echo "usage: playerid roundid" && exit 1

ROUNDRESULTQ="SELECT id FROM kisakone_RoundResult WHERE round = $ROUNDID"
SCOREQ="SELECT * FROM kisakone_HoleResult WHERE player = $PLAYERID AND roundresult IN ($ROUNDRESULTQ)"
ROUNDQ="SELECT * FROM kisakone_RoundResult WHERE player = $PLAYERID AND round = $ROUNDID"

cat <<EOF >$FILE
$SCOREQ;
$ROUNDQ;
EOF

mysql -v -u root --password=pass fliitto_yleinen <$FILE
rm $FILE
