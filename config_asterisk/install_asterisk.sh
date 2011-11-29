#!/bin/sh
VERSION='0.0.2'
WGET=`which wget 2>/dev/null`
PORT='5544'
clear
echo ""
echo ""
echo "Версия $VERSION"
echo "press any key to continue or CTRL-C to exit"
read TEMP


IFCONFIG=`which ifconfig 2>/dev/null||echo /sbin/ifconfig`
IPADDR=`$IFCONFIG |gawk '/inet addr/{print $2}'|gawk -F: '{print $2}'`
echo "$IPADDR"
##################################### Настраиваем Астериск #####################################
############################### MySQL 
echo "; Сгенерированно скриптом от Сашки v.$VERSION
[global]
hostname = localhost
dbname = asterisk
password = AsTeRiXpWd
user = asterisk
userfield=1
;port=3306
;sock=/tmp/mysql.sock
" > /etc/asterisk/cdr_mysql.conf

############################### Loging
echo "; Сгенерированно скриптом от Сашки v.$VERSION
[general]
;dateformat=%F %T       ; ISO 8601 date format
;dateformat=%F %T.%3q   ; with milliseconds
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
" > /etc/asterisk/cdr_mysql.conf

############################### Aliases
echo "; Сгенерированно скриптом от Сашки v.$VERSION
sreg=sip show registry
speer=sip show peers
sdeb=sip set debug
" > /etc/asterisk/aliases

############################### SIP.CONF
echo "; Сгенерированно скриптом от Сашки v.$VERSION
[general]
#include sip_general.conf
#include sip_registrations.conf
#include sip_peers.conf
#include sip_trunks.conf
" > /etc/asterisk/sip.conf

echo "; Сгенерированно скриптом от Сашки v.$VERSION
bindport=$PORT
externip=
localnet=
qualyfiy=yes
nat=yes
canreinvite=no
useragent=Magic
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

;allowguest=no                  ; Allow or reject guest calls (default is yes)
;match_auth_username=yes        ; if available, match user entry using the
;allowtransfer=no               ; Disable all transfers (unless enabled in peers or users)
;realm=mydomain.tld             ; Realm for digest authentication
;udpbindaddr=0.0.0.0             ; IP address to bind UDP listen socket to (0.0.0.0 binds to all)
;tlsenable=no                   ; Enable server for incoming TLS (secure) connections (default is no)
;tlsbindaddr=0.0.0.0            ; IP address for TLS server to bind to (0.0.0.0) binds to all interfaces)
;tlscertfile=asterisk.pem       ; Certificate file (*.pem only) to use for TLS connections
;tlscafile=</path/to/certificate>
;tlscadir=</path/to/ca/dir>
;tlsdontverifyserver=[yes|no]
;tlscipher=<SSL cipher string>
;tcpauthtimeout = 30            ; tcpauthtimeout specifies the maximum number
;tcpauthlimit = 100             ; tcpauthlimit specifies the maximum number of
;pedantic=yes                   ; Enable checking of tags in headers,
;tos_sip=cs3                    ; Sets TOS for SIP packets.
;tos_audio=ef                   ; Sets TOS for RTP audio packets.
;tos_video=af41                 ; Sets TOS for RTP video packets.
;tos_text=af41                  ; Sets TOS for RTP text packets.
;cos_sip=3                      ; Sets 802.1p priority for SIP packets.
;cos_audio=5                    ; Sets 802.1p priority for RTP audio packets.
;cos_video=4                    ; Sets 802.1p priority for RTP video packets.
;cos_text=3                     ; Sets 802.1p priority for RTP text packets.
;maxexpiry=3600                 ; Maximum allowed time of incoming registrations
;minexpiry=60                   ; Minimum length of registrations/subscriptions (default 60)
;defaultexpiry=120              ; Default length of incoming/outgoing registration
;mwiexpiry=3600                 ; Expiry time for outgoing MWI subscriptions
;qualifyfreq=60                 ; Qualification: How often to check for the
;qualifygap=100                 ; Number of milliseconds between each group of peers being qualified
;qualifypeers=1                 ; Number of peers in a group to be qualified at the same time
;notifymimetype=text/plain      ; Allow overriding of mime type in MWI NOTIFY
;buggymwi=no                    ; Cisco SIP firmware doesn't support the MWI RFC
;vmexten=voicemail              ; dialplan extension to reach mailbox sets the
;disallow=all                   ; First disallow all codecs
;allow=ulaw                     ; Allow codecs in order of preference
;allow=ilbc                     ; see doc/rtp-packetization for framing options
;mohinterpret=default
;mohsuggest=default
;parkinglot=plaza               ; Sets the default parking lot for call parking
;language=en                    ; Default language setting for all users/peers
;relaxdtmf=yes                  ; Relax dtmf handling
;trustrpid = no                 ; If Remote-Party-ID should be trusted
;sendrpid = yes                 ; If Remote-Party-ID should be sent
;prematuremedia=no              ; Some ISDN links send empty media frames before
;progressinband=never           ; If we should generate in-band ringing always
;sdpsession=Asterisk PBX        ; Allows you to change the SDP session name string, (s=)
;sdpowner=root                  ; Allows you to change the username field in the SDP owner string, (o=)
;promiscredir = no              ; If yes, allows 302 or REDIR to non-local SIP address
;usereqphone = no               ; If yes, \";user=phone\" is added to uri that contains
;dtmfmode = rfc2833             ; Set default dtmfmode for sending DTMF. Default: rfc2833
;compactheaders = yes           ; send compact sip headers.
;videosupport=yes               ; Turn on support for SIP video. You need to turn this
;maxcallbitrate=384             ; Maximum bitrate for video calls (default 384 kb/s)
;callevents=no                  ; generate manager events when sip ua
;authfailureevents=no           ; generate manager \"peerstatus\" events when peer can't
;alwaysauthreject = yes         ; When an incoming INVITE or REGISTER is to be rejected,
;g726nonstandard = yes          ; If the peer negotiates G726-32 audio, use AAL2 packing
;outboundproxy=proxy.provider.domain            ; send outbound signaling to this proxy, not directly to the devices
;outboundproxy=proxy.provider.domain:8080       ; send outbound signaling to this proxy, not directly to the devices
;outboundproxy=proxy.provider.domain,force      ; Send ALL outbound signalling to proxy, ignoring route: headers
;outboundproxy=tls://proxy.provider.domain      ; same as '=proxy.provider.domain' except we try to connect with tls
;matchexterniplocally = yes     ; Only substitute the externip or externhost setting if it matches
;dynamic_exclude_static = yes   ; Disallow all dynamic hosts from registering
;contactdeny=0.0.0.0/0.0.0.0           ; Use contactpermit and contactdeny to
;contactpermit=172.16.0.0/255.255.0.0  ; restrict at what IPs your users may
;forwardloopdetected=no         ; Attempt to forward a call locally if the
;shrinkcallerid=yes     ; on by default
;regcontext=sipregistrations
;regextenonqualify=yes          ; Default \"no\"
;t1min=100                      ; Minimum roundtrip time for messages to monitored hosts
;timert1=500                    ; Default T1 timer
;timerb=32000                   ; Call setup timer. If a provisional response is not received
;rtptimeout=60                  ; Terminate call if 60 seconds of no RTP or RTCP activity
;rtpholdtimeout=300             ; Terminate call if 300 seconds of no RTP or RTCP activity
;rtpkeepalive=<secs>            ; Send keepalives in the RTP stream to keep NAT open
;session-timers=originate
;session-expires=600
;session-minse=90
;session-refresher=uas
;sipdebug = yes                 ; Turn on SIP debugging by default, from
;recordhistory=yes              ; Record SIP history by default
;dumphistory=yes                ; Dump SIP history at end of SIP dialogue
;allowsubscribe=no              ; Disable support for subscriptions. (Default is yes)
;subscribecontext = default     ; Set a specific context for SUBSCRIBE requests
;notifyringing = no             ; Control whether subscriptions already INUSE get sent
;notifyhold = yes               ; Notify subscriptions on HOLD state (default: no)
;notifycid = yes                ; Control whether caller ID information is sent along with
;callcounter = yes              ; Enable call counters on devices. This can be set per
;register => 1234:password@mysipprovider.com
;register => 2345:password@sip_proxy/1234
;register => 3456@mydomain:5082::@mysipprovider.com
;register => tls://username:xxxxxx@sip-tls-proxy.example.org
;registertimeout=20             ; retry registration calls every 20 seconds (default)
;registerattempts=10            ; Number of registration attempts before we give up
;mwi => 1234:password@mysipprovider.com/1234
;directmedia=yes                ; Asterisk by default tries to redirect the
;directrtpsetup=yes             ; Enable the new experimental direct RTP setup. This sets up
;directmedia=nonat              ; An additional option is to allow media path redirection
;directmedia=update             ; Yet a third option... use UPDATE for media path redirection,
;ignoresdpversion=yes           ; By default, Asterisk will honor the session version
;rtcachefriends=yes             ; Cache realtime friends by adding them to the internal list
;rtsavesysname=yes              ; Save systemname in realtime database at registration
;rtupdate=yes                   ; Send registry updates to database using realtime? (yes|no)
;rtautoclear=yes                ; Auto-Expire friends created on the fly on the same schedule
;ignoreregexpire=yes            ; Enabling this setting has two functions:
;domain=mydomain.tld,mydomain-incoming
;domain=1.2.3.4                 ; Add IP address as local domain
;allowexternaldomains=no        ; Disable INVITE and REFER to non-local domains
;autodomain=yes                 ; Turn this on to have Asterisk add local host
;auth=mark:topsecret@digium.com
;type=peer
;context=from-fwd
;host=fwd.pulver.com
;type=peer                        ; we only want to call out, not be called
;remotesecret=guessit             ; Our password to their service
;defaultuser=yourusername         ; Authentication user for outbound proxies
;fromuser=yourusername            ; Many SIP providers require this!
;fromdomain=provider.sip.domain
;host=box.provider.com
;transport=udp,tcp                ; This sets the default transport type to udp for outgoing, and will
;usereqphone=yes                  ; This provider requires \";user=phone\" on URI
;callcounter=yes                  ; Enable call counter
;busylevel=2                      ; Signal busy at 2 or more calls
;outboundproxy=proxy.provider.domain  ; send outbound signaling to this proxy, not directly to the peer
;port=80                          ; The port number we want to connect to on the remote side
;type=peer
;host=sip.provider1.com
;fromuser=4015552299              ; how your provider knows you
;remotesecret=youwillneverguessit ; The password we use to authenticate to them
;secret=gissadetdu                ; The password they use to contact us
;callbackextension=123            ; Register with this server and require calls coming back to this extension
;transport=udp,tcp                ; This sets the transport type to udp for outgoing, and will
;type=friend
;context=from-sip                ; Where to start in the dialplan when this phone calls
;callerid=John Doe <1234>        ; Full caller ID, to override the phones config
;host=192.168.0.23               ; we have a static but private IP address
;nat=no                          ; there is not NAT between phone and Asterisk
;directmedia=yes                 ; allow RTP voice traffic to bypass Asterisk
;dtmfmode=info                   ; either RFC2833 or INFO for the BudgeTone
;call-limit=1                    ; permit only 1 outgoing call and 1 incoming call at a time
;mailbox=1234@default            ; mailbox 1234 in voicemail context \"default\"
;disallow=all                    ; need to disallow=all before we can use allow=
;allow=ulaw                      ; Note: In user sections the order of codecs
;allow=alaw
;allow=g723.1                    ; Asterisk only supports g723.1 pass-thru!
;allow=g729                      ; Pass-thru only unless g729 license obtained
;callingpres=allowed_passed_screen ; Set caller ID presentation
;type=friend
;regexten=1234                   ; When they register, create extension 1234
;callerid=\"Jane Smith\" <5678>
;host=dynamic                    ; This device needs to register
;nat=yes                         ; X-Lite is behind a NAT router
;directmedia=no                  ; Typically set to NO if behind NAT
;disallow=all
;allow=gsm                       ; GSM consumes far less bandwidth than ulaw
;allow=ulaw
;allow=alaw
;mailbox=1234@default,1233@default ; Subscribe to status of multiple mailboxes
;registertrying=yes              ; Send a 100 Trying when the device registers.
;type=friend                     ; Friends place calls and receive calls
;context=from-sip                ; Context for incoming calls from this user
;secret=blah
;subscribecontext=localextensions ; Only allow SUBSCRIBE for local extensions
;language=de                     ; Use German prompts for this user
;host=dynamic                    ; This peer register with us
;dtmfmode=inband                 ; Choices are inband, rfc2833, or info
;defaultip=192.168.0.59          ; IP used until peer registers
;mailbox=1234@context,2345       ; Mailbox(-es) for message waiting indicator
;subscribemwi=yes                ; Only send notifications if this phone
;vmexten=voicemail               ; dialplan extension to reach mailbox
;disallow=all
;allow=ulaw                      ; dtmfmode=inband only works with ulaw or alaw!
;type=friend                     ; Friends place calls and receive calls
;context=from-sip                ; Context for incoming calls from this user
;secret=blahpoly
;host=dynamic                    ; This peer register with us
;dtmfmode=rfc2833                ; Choices are inband, rfc2833, or info
;defaultuser=polly               ; Username to use in INVITE until peer registers
;defaultip=192.168.40.123
;disallow=all
;allow=ulaw                      ; dtmfmode=inband only works with ulaw or alaw!
;progressinband=no               ; Polycom phones don't work properly with \"never\"
;type=friend
;secret=blah
;host=dynamic
;insecure=port                   ; Allow matching of peer by IP address without
;insecure=invite                 ; Do not require authentication of incoming INVITEs
;insecure=port,invite            ; (both)
;qualify=1000                    ; Consider it down if it's 1 second to reply
;qualifyfreq=60                  ; Qualification: How often to check for the
;callgroup=1,3-4                 ; We are in caller groups 1,3,4
;pickupgroup=1,3-5               ; We can do call pick-p for call group 1,3,4,5
;defaultip=192.168.0.60          ; IP address to use if peer has not registered
;deny=0.0.0.0/0.0.0.0            ; ACL: Control access to this account based on IP address
;permit=192.168.0.60/255.255.255.0
;permit=192.168.0.60/24          ; we can also use CIDR notation for subnet masks
;type=friend
;secret=blah
;qualify=200                     ; Qualify peer is no more than 200ms away
;nat=yes                         ; This phone may be natted
;host=dynamic                    ; This device registers with us
;directmedia=no                  ; Asterisk by default tries to redirect the
;defaultip=192.168.0.4           ; IP address to use until registration
;defaultuser=goran               ; Username to use when calling this device before registration
;setvar=CUSTID=5678              ; Channel variable to be set for all calls from or to this device
;setvar=ATTENDED_TRANSFER_COMPLETE_SOUND=beep   ; This channel variable will
;type=friend
;secret=digium
;host=dynamic
;rfc2833compensate=yes          ; Compensate for pre-1.4 DTMF transmission from another Asterisk machine.
;t38pt_usertpsource=yes         ; Use the source IP address of RTP as the destination IP address for UDPTL packets

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
host=home.tarakanovs.ru
port=8222
context=sos

;[telphin](trunk)
;username=00000000
;fromuser=00000
;defaultuser=00000000
;password=password
;host=sip.telphin.com
;fromdomain=sip.telphin.com
;port=5068
;context=telphin

;[zadarma](trunk)
;username=00000
;fromuser=00000
;defaultuser=00000
;password=password
;host=sip.zadarma.com
;fromdomain=sip.zadarma.com
;context=zadarma

;[sipgate](trunk)
;username=0000000
;fromuser=00000
;defaultuser=0000000
;password=password
;host=sip.sipgate.ru
;context=sipgate

;[sipnet](trunk)
;secret=00000
;defaultuser=00000
;host=sipnet.ru
;fromuser=00000
;fromdomain=sipnet.ru
;context=sipnet

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


[101](peers)
username=101
secret=

[102](peers)
username=102
secret=

[103](peers)
username=103
secret=

[104](peers)
username=104
secret=

[105](peers)
username=105
secret=

[106](peers)
username=106
secret=

[107](peers)
username=107
secret=

[108](peers)
username=108
secret=

[109](peers)
username=109
secret=


" > /etc/asterisk/sip_peers.conf

echo "; Сгенерированно скриптом от Сашки v.$VERSION
;register => 00000:password@sip.zadarma.com
;register => 00000000:password@sip.telphin.com:5068
;register => 0000000:password@sip.sipgate.ru

" > /etc/asterisk/sip_registrations.conf

############################### extension.conf
echo "
; добавлено Сашкиным скриптом v.$VERSION
#include extensions_alex.conf
" >> /etc/asterisk/extensions.conf

echo "
; Сгенерированно скриптом от Сашки v.$VERSION
[nocontext]
exten _X.,1,Hangup()

[office]
exten => _XXX,1,NoOp(internal phones \${EXTEN} status - \${SIPPEER(\${EXTEN},status)})
exten => _XXX,n,Dial(SIP/\${EXTEN},30,m)
exten => _XXX,n,Hangup()

#Дозваниваемся до тех. поддержки, т.е. до меня из контекста ОФИС
exten => 911,1,Dial(SIP/\${EXTEN}@sos)
exten => 911,n,Hangup

#Когда я захочу дозвониться до когото в офисе, я тут дложен подправить
[sos]
exten => s,1,NoOp(Extentions s)
exten => s,n,Hangup
exten => _X.,1,NoOp(Extentions _X.)
exten => _X.,n,Hangup


" > /etc/asterisk/extensions_alex.conf


