#!/bin/sh
PASSWD='AlExAnDeRpWd'

TERMINATECALLIP='80.246.254.114'
TERMINATECALLPORT='5544'

NUMTELPHIN=""
PASTELPHIN=""

NAT=no

SOSPORT="8222"
SOSHOST="home.tarakanovs.ru"

FAXFROMEMAIL="help@yandex.ru"
FAXTOEMAIL="nomail@nomail.ru"

USERAGENT="Planet"

STARTPEER=0
STOPPEER=0

VERSION='0.0.4'
PORT='5544'
clear

echo ""
echo ""
echo "Версия $VERSION"
echo "press any key to continue or CTRL-C to exit"
read TEMP

#################### добавили в внутреннию базу астериска дневной/ночной режимы #########################
/usr/sbin/asterisk -rx "database put  DAYNIGHT pstn DAY"

IFCONFIG=`which ifconfig 2>/dev/null||echo /sbin/ifconfig`
IPADDR=`$IFCONFIG |gawk '/inet addr/{print $2}'|gawk -F: '{print $2}' |grep -v 127.0`

#### можно проверить ip еще и так 
###IPADDR=`wget -qO- http://internet.yandex.ru/ | gawk '/Мой IP:/{print $5}' |gawk -F\< '{print $1}'`

echo "$IPADDR"
##################################### Настраиваем Астериск #####################################
############################### MySQL 
echo "; Сгенерированно скриптом от Сашки v.$VERSION
[global]
hostname = localhost
dbname = asterisk
password = $PASSWD
user = root
userfield=1
;port=3306
;sock=/tmp/mysql.sock
" > /etc/asterisk/cdr_mysql.conf

############################### Loging asterisk
echo "; Сгенерированно скриптом от Сашки v.$VERSION
[general]
;dateformat=%F %T       ; ISO 8601 date format
dateformat=%F %T.%3q   ; with milliseconds
;appendhostname = yes
;queue_log = no
;queue_log_name = queue_log
;rotatestrategy = rotate
; exec_after_rotate=gzip -9 \${filename}.2
;event_log = no

[logfiles]
;debug => debug
;console => notice,warning,error,debug
;syslog keyword : This special keyword logs to syslog facility
;syslog.local0 => notice,warning,error
console => notice,warning,error
messages => notice,warning,error
full => notice,warning,error,debug,verbose
" > /etc/asterisk/logger.conf


############################### Aliases #########################################
sed -i -e 's/^;template = individual_custom/template = individual_custom/g' /etc/asterisk/cli_aliases.conf
sed -i -e 's/^;#include "\/etc\/asterisk\/aliases"/#include "\/etc\/asterisk\/aliases"/g' /etc/asterisk/cli_aliases.conf


echo "; Сгенерированно скриптом от Сашки v.$VERSION
sreg=sip show registry
speer=sip show peers
sdeb=sip set debug
restart=core restart now
" > /etc/asterisk/aliases



############################################### MOH ##################################################
echo "
#include musiconhold_custom.conf
" >> /etc/asterisk/musiconhold.conf

echo "
[silence]
mode=files
directory=/var/lib/asterisk/moh/gz
" > /etc/asterisk/musiconhold_custom.conf


######################################################################################################
############################################# SIP.CONF ###############################################
######################################################################################################
######################################################################################################
echo "; Сгенерированно скриптом от Сашки v.$VERSION
[general]
#include sip_general.conf
;#include sip_registrations.conf
#include sip_registrations_mf.conf

;#include sip_peers.conf
;#include sip_trunks.conf
#include sip_peer_mf.conf
#include sip_peer_terminat.conf

" > /etc/asterisk/sip.conf
touch /etc/asterisk/sip_general.conf
touch /etc/asterisk/sip_registrations.conf
touch /etc/asterisk/sip_registrations_mf.conf
touch /etc/asterisk/sip_peers.conf
touch /etc/asterisk/sip_trunks.conf
touch /etc/asterisk/sip_peer_mf.conf
touch /etc/asterisk/sip_peer_terminat.conf

echo "; Сгенерированно скриптом от Сашки v.$VERSION
[terminatecall]
host=$TERMINATECALLIP
port=$TERMINATECALLPORT
qualify=yes
type=peer
nat=no
insecure=invite,port
canreinvite=no
disallow=all
allow=ulaw
allow=all
" > /etc/asterisk/sip_peer_terminat.conf




echo "; Сгенерированно скриптом от Сашки v.$VERSION
bindport=$PORT
externip=$IPADDR
;localnet=$LANNET
qualyfiy=yes
nat=$NAT
canreinvite=no
useragent=$USERAGENT
vmexten=*97
disallow=all
allow=ulaw
allow=alaw
context=nocontext
callerid=Unknown
notifyringing=yes
notifyhold=yes
limitonpeers=yes
tos_sip=cs3
tos_audio=ef
tos_video=af41
alwaysauthreject=yes

" > /etc/asterisk/sip_general.conf

echo "; Сгенерированно скриптом от Сашки v.$VERSION
[trunk](!)
qualify=yes
type=peer
nat=no
insecure=invite,port
canreinvite=no
disallow=all
allow=ulaw
allow=all

[sos](trunk)
type=friend
host=$SOSHOST
port=$SOSPORT
context=sos

[trunk1](trunk)
username=$NUMTELPHIN
fromuser=$NUMTELPHIN
defaultuser=$NUMTELPHIN
password=$PASTELPHIN
host=sip.telphin.com
fromdomain=sip.telphin.com
port=5068
context=from-trunk

" > /etc/asterisk/sip_trunks.conf

echo "; Сгенерированно скриптом от Сашки v.$VERSION
[peers](!)
host=dynamic
qualify=yes
type=friend
nat=no
insecure=invite,port
canreinvite=no
disallow=all
allow=ulaw
allow=all
context=office



" > /etc/asterisk/sip_peers.conf

for (( c=$STARTPEER; $c<$STOPPEER; c=$c+1 ));
do
rnd=`cat /dev/urandom |tr -dc A-Za-z0-9| (head -c $1 > /dev/null 2>&1 || head -c 8)`
let peer=100+$c
echo "
[$peer](peers)
username=$peer
secret=$rnd
" >> /etc/asterisk/sip_peers.conf
done
            


echo "; Сгенерированно скриптом от Сашки v.$VERSION
register => $NUMTELPHIN:$PASTELPHIN@sip.telphin.com:5068

" > /etc/asterisk/sip_registrations.conf




############################### extension.conf
echo "
; добавлено Сашкиным скриптом v.$VERSION
;#include extensions_alex.conf
;############## Для мультифона созадили отдельный файл
#include extensions_mf.conf
#include extensions_obzvon.conf
" > /etc/asterisk/extensions.conf

echo "
; Сгенерированно скриптом от Сашки v.$VERSION
[nocontext]
exten _X.,1,Hangup()
exten s,1,Hangup()

;################################################## Локальный офисный контекст ###########################################
[office]
;######################## локальные звонки ########################
exten => _XXX,1,NoOp(internal phones \${EXTEN} status - \${SIPPEER(\${EXTEN},status)})
exten => _XXX,n,Set(CHANNEL(language)=ru)
exten => _XXX,n,AGI(record.agi,lo,\${CALLERID(num)},\${EXTEN})
exten => _XXX,n,Set(NUMBER=\${EXTEN})
exten => _XXX,n,GotoIf(\$[\"\${SIPPEER(\${EXTEN},status)}\" = '']?number_exists)
exten => _XXX,n,GotoIf(\$[\"\${SIPPEER(\${EXTEN},status):0:2}\" = \"UN\"]?number_not_connected)
exten => _XXX,n,Dial(SIP/\${EXTEN},30,m)
exten => _XXX,n,Goto(s-\${DIALSTATUS},1)
exten => _XXX,n,Hangup()
exten => _XXX,n(number_not_connected),Voicemail(\${NUMBER},u)
exten => _XXX,n,Hangup()
exten => _XXX,n(number_exists),Playback(pbx-invalid)
exten => _XXX,n,Hangup()

exten => s-NOANSWER,1,Voicemail(\${NUMBER},u)
exten => s-CHANUNAVAIL,1,Voicemail(\${NUMBER},b)
exten => s-BUSY,1,Voicemail(\${NUMBER},b)
exten => _s-.,1,Voicemail(\${NUMBER},u)

;######################################### перевод станции в разные режимы ##############################
exten => 53,1,NoOp(fw to mobile)
exten => 53,n,Set(CHANNEL(language)=ru)
exten => 53,n,Set(DB(DAYNIGHT/pstn)=FW)
exten => 53,n,Playback(beep&chfw&beep)
exten => 53,n,Hangup

exten => 54,1,NoOp(Night)
exten => 54,n,Set(CHANNEL(language)=ru)
exten => 54,n,Set(DB(DAYNIGHT/pstn)=NIGHT)
exten => 54,n,Playback(beep&chnight&beep)
exten => 54,n,Hangup

exten => 55,1,NoOp(Day)
exten => 55,n,Set(CHANNEL(language)=ru)
exten => 55,n,Set(DB(DAYNIGHT/pstn)=DAY)
exten => 55,n,Playback(beep&chday&beep)
exten => 55,n,Hangup
;######################################### перевод станции в разные режимы ##############################

exten => *98,1,Set(CHANNEL(language)=ru)
exten => *98,n,VoiceMailMain()

;#exten => 000,1,Set(FAXFILE=/var/spool/asterisk/fax/\${STRFTIME(\${EPOCH},,%H.%M.%S)}_\${CALLERID(num)}.tiff)
;#exten => 000,n,ReceiveFAX(\${FAXFILE})
;#exten => 000,n,System('/bin/sendEmail -f FAXFROMEMAIL -t FAXTOEMAIL -a \${FAXFILE} -u \"Fax from \${CALLERID(num)}\" -m \"Recive fax\nFrom Asterisk \n local call \${STRFTIME(\${EPOCH},,%H.%M.%S)}\" -s smtp.yandex.ru')

;############## puckup ####################
exten => *,1,Pickup(\${EXTEN:1})
;############## puckup ####################

;############## chanspy ###################
exten => *00,1,ChanSpy(all,oq)
;############## chanspy ###################

;############################# исходящие звонки от Телфина ##################################
exten => _9X.,1,NoOp(Call to World - Telphin )
exten => _9X.,n,AGI(record.agi,out9,\${CALLERID(num)},\${EXTEN:1})
exten => _9X.,n,Dial(SIP/\${EXTEN:1}@\${trunk1},,S(3600))
exten => _9X.,n,Hangup

include => ivrrecord
;###################################################
;#Дозваниваемся до тех. поддержки, т.е. до меня из контекста ОФИС
exten => 911,1,Dial(SIP/\${EXTEN}@sos)
exten => 911,n,Hangup

;#Когда я захочу дозвониться до когото в офисе, я тут дложен подправить
[sos]
exten => s,1,NoOp(Context sos Extentions s)
exten => s,n,Hangup
exten => _X.,1,NoOp(Context sos Extentions _X.)
exten => _X.,n,Hangup


" > /etc/asterisk/extensions_alex.conf

echo "
[ivrrecord]
" >> /etc/asterisk/extensions_alex.conf

for (( c=0; $c<11; c=$c+1 ));
do
let startrecord=601+$c
let startplayback=701+$c
echo "
exten => $startrecord,1,Wait(1)
exten => $startrecord,n,Record(/var/lib/asterisk/sounds/$startrecord:gsm)
exten => $startrecord,n,Wait(1)
exten => $startrecord,n,Playback(/var/lib/asterisk/sounds/$startrecord)
exten => $startrecord,n,Hangup
exten => $startplayback,1,Wait(1)
exten => $startplayback,n,Playback(/var/lib/asterisk/sounds/$startrecord)
exten => $startplayback,n,Hangup

" >> /etc/asterisk/extensions_alex.conf

done
################################################# для обзвонка 
echo "
[diallocal]
exten => _X.,1,NoOp(Start Dial)
exten => _X.,n,Set(T1=\${EPOCH})
exten => _X.,n,Dial(SIP/\${phone}@\${line},130,S(1200))
exten => _X.,n,Hangup

exten => h,1,Set(T2=\$[\${EPOCH}-\${T1}])
exten => h,n,System(/bin/echo \"\${STRFTIME(\${EPOCH},,%Y.%m.%d)}-\${STRFTIME(\${EPOCH},,%H.%M.%S)}\;\${line}\;stat\${HANGUPCAUSE}\;\${DIALSTATUS}\;\${DIALEDTIME}\;\${ANSWEREDTIME}\;\${T2}\" >> /home/\${STRFTIME(\${EPOCH},,%Y%m%d)}-\${line}-err.csv)
;###################################################################################################
;################################## Bridging SIP channels ##########################################
[call-bridge]
exten => s,1,NoOp(BINGO!!!!!!)
exten => s,n,Set(T1=\${EPOCH})
exten => s,n,System('/bin/rm -f /var/spool/asterisk/outgoing/*')
exten => s,n,AGI(record.agi,bingo,\${line},local)
exten => s,n,Dial(SIP/777@terminatecall,130,gm(silence)S(600))
;exten => s,n,System('/var/lib/asterisk/call.pl')
exten => s,n,Wait(1)
exten => s,n,Hangup

;#заносим в базу значение и запускаем поновой обзвон
exten => h,1,Set(T2=\$[\${EPOCH}-\${T1}])
exten => h,n,System(/bin/echo \"\${STRFTIME(\${EPOCH},,%Y.%m.%d)}-\${STRFTIME(\${EPOCH},,%H.%M.%S)}\;\${line}\;stat\${HANGUPCAUSE}\;\${DIALSTATUS}\;\${DIALEDTIME}\;\${ANSWEREDTIME}\;\${T2}\" >> /home/\${STRFTIME(\${EPOCH},,%Y%m%d)}-aaa.csv)
;#Если пришел звонок с транка - ебашим на звонок локальному абоненту
exten => _X.,1,Set(line=server)
exten => _X.,n,GoTo(s,1)

" > /etc/asterisk/extensions_obzvon.conf
service asterisk restart
