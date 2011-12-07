#!/usr/bin/perl
$root_dir="/var/lib/asterisk";
my %multifon = ();
my $file_phone="$root_dir/phone.txt";
open (PHONE, "< $file_phone");
 while (<PHONE>) {
    chomp($_);
     if ($_=~ /^#.*/) {
      next;
     }
    ($key1,$value1)=split(";",$_);
    $multifon{"$key1"}="$value1";
 }
close(PHONE);


my $file_peer="/etc/asterisk/sip_peer_mf.conf";
my $file_reg="/etc/asterisk/sip_registrations_mf.conf";
my $file_ext="/etc/asterisk/extensions_mf.conf";

######################################### sip_registration.conf #####################################
my $count=0;
open (REG, "> $file_reg");
while ( my ($key, $value) = each(%multifon) ) {
    print REG "register => $key:$value\@multifon.ru/$key\n";
    print  ++$count, " register => $key:$value\@multifon.ru/$key\n";
}
close(REG);

######################################### sip.conf ##################################################
open (PEER, "> $file_peer");
while ( my ($key, $value) = each(%multifon) ) {
print PEER "
\n[$key]
username=$key
type=friend
secret=$value
qualify=yes
nat=no
insecure=port,invite
host=193.201.229.35
fromuser=$key
fromdomain=multifon.ru
dtmfmode=inband
context=multifon-in
canreinvite=no
disallow=all
allow=ulaw
allow=alaw
\n";
}
close(PEER);

####################################### extention.conf ##########################################
my $i=100;
open (EXT, "> $file_ext");
print EXT "[multifon-out]\n";
while ( my ($key, $value) = each(%multifon) ) {
++$i;
print EXT "
exten => _$i\X.,1,NoOp(\"out-multifon-$i-$key\")
exten => _$i\X.,n,Dial(SIP/\${EXTEN:3}\@$key)";
}

print EXT "[multifon-in]\n";
while ( my ($key, $value) = each(%multifon) ) {
++$i;
print EXT "exten => $key,1,NoOp(\"in-multifon-$key\")
exten => $key,n,Dial(SIP/100)
exten => $key,n,Hangup\n";
}
close(EXT);

print `/usr/sbin/asterisk -rx "sip reload"`;
print `/usr/sbin/asterisk -rx "dialplan reload"`;
