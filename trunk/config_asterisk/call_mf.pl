#!/usr/bin/perl
my @multifon=();
my $file_phone="/var/lib/asterisk/phone.txt";
open (PHONE, "< $file_phone");
 while (<PHONE>) {
    chomp($_);
     if ($_=~ /^#.*/) {
      next;
     }
    ($key1,$value1)=split(";",$_);
    push(@multifon,$key1);
 }
close(PHONE);


my $dst_phone="";

my $file_dir="/tmp";
my $dst_file_dir="/var/spool/asterisk/outgoing";


foreach (@multifon) {
sleep(1);
open(GOG,"> $file_dir/$_.call");
print GOG "Channel: Local/$dst_phone\@diallocal\n";
print GOG "MaxRetries: 300\n";
print GOG "RetryTime: 1\n";
print GOG "WaitTime: 1600\n";
print GOG "Context: call-bridge\n";
print GOG "Extension: s\n";
print GOG "Priority: 1\n";
print GOG "set: phone=$dst_phone\n";
print GOG "set: line=$_\n\n";
close(GOG);
print `chown asterisk:asterisk $file_dir/$_.call`;
print `mv -f "$file_dir/$_.call" "$dst_file_dir/$_.call"`;
}

exit 0;

