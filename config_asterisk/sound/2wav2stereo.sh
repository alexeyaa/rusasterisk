#!/bin/sh
SOX=/usr/bin/sox
SOXMIX=/usr/bin/soxmix


# command line variables
LEFT="$1"
RIGHT="$2"
OUT="$3"

test ! -r $LEFT && exit 21
test ! -r $RIGHT && exit 22

# left channel
$SOX $LEFT -c 2 $LEFT-tmp.wav pan -1
# right channel
$SOX $RIGHT -c 2 $RIGHT-tmp.wav pan 1
$SOXMIX -v 1 $LEFT-tmp.wav -v 1 $RIGHT-tmp.wav -v 1 $OUT


#remove temporary files
test -w $LEFT-tmp.wav && rm $LEFT-tmp.wav
test -w $RIGHT-tmp.wav && rm $RIGHT-tmp.wav
test -w $LEFT && rm $LEFT
test -w $RIGHT && rm $RIGHT

#test -w $OUT.wav && rm $OUT.wav

# eof
