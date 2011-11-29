#!/bin/sh
#Скрипт устанавливает нам Астериск
ROOT_DIR='/root'
HW='i386'
#HW='x86_64'
VERSION="0.0.2"
WGET=`which wget 2>/dev/null`
#WGETOPT=
clear

IFCONFIG=`which ifconfig 2>/dev/null||echo /sbin/ifconfig`
IPADDR=`$IFCONFIG |gawk '/inet addr/{print $2}'|gawk -F: '{print $2}'`
echo "$IPADDR"

echo "Версия скрипта $VERSION"
echo "press any key to continue or CTRL-C to exit"
read TEMP


##################################### Скачиваем и ставим Asterisk начинаем с репозиториев  #####################################
echo "
[asterisk-current]
name=CentOS-\$releasever - Asterisk - Current
baseurl=http://packages.asterisk.org/centos/\$releasever/current/\$basearch/
enabled=1
gpgcheck=0
#gpgkey=http://packages.asterisk.org/RPM-GPG-KEY-Digium
" > /etc/yum.repos.d/centos-asterisk.repo


echo "
[digium-current]
name=CentOS-\$releasever - Digium - Current
baseurl=http://packages.digium.com/centos/\$releasever/current/\$basearch/
enabled=1
gpgcheck=0
#gpgkey=http://packages.digium.com/RPM-GPG-KEY-Digium
" > /etc/yum.repos.d/centos-digium.repo

##################################### Скачиваем и ставим Asterisk   #####################################
echo "yum -y install asterisk"
yum -y install yum-repos-asterisk.noarch
yum -y install mc.$HW
yum -y install asterisk16.$HW 
yum -y install asterisk16-addons.$HW 
#yum -y install asterisk16-addons-mysql.$HW
yum -y install asterisk16-configs.$HW
yum -y install sox.$HW

##################################### Скачиваем AGI #####################################
FILEAGI="asterisk-perl-1.01"
AGIURL="http://search.cpan.org/CPAN/authors/id/J/JA/JAMESGOL/$FILEAGI.tar.gz"
cd ./$ROOT_DIR
$WGET $AGIURL
#В дальнейшем необходимо подправить gawk!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
echo "tar xvzf ./$FILEAGI.tar.gz"
tar xvzf ./$FILEAGI.tar.gz
cd ./$FILEAGI
echo "perl Makefile.PL"
perl Makefile.PL
echo "make all"
make all
echo "make install"
make install

rm -f $ROOT_DIR/$FILEAGI

##################################### Скачиваем и ставим Apache  ###################################

yum -y install httpd.$HW
echo "Listen 323" >>/etc/httpd/conf/httpd.conf
echo "
<VirtualHost *:323>
 ServerName asterisk.localhost
 ServerAlias *.asterisk.localhost
 ServerAdmin 101@3090607.ru
 ErrorLog /var/log/httpd/asterisk.err
 CustomLog /var/log/httpd/asterisk.log combined
 DocumentRoot /var/www/html/wavplayer/
 <Directory \"/var/www/html/wavplayer/\">
   DirectoryIndex index.php index.html
   Options FollowSymLinks +Indexes
   Order allow,deny
   Allow from all
 </Directory>
</VirtualHost>
" > /etc/httpd/conf.d/asterisk.conf

echo "Hellow World" > /var/www/html/index.html

##################################### Скачиваем и ставим MySQL #####################################
cd $ROOT_DIR
FILE='sql.txt'
$WGET $WGETOPT http://3090607.ru/asterisk/$FILE
echo "Download sql.txt"

yum -y install mysql-server.$HW
service mysqld start
/usr/bin/mysqladmin -u root password 'AlExAnDeRpWd'
cd $ROOT_DIR
mysql --user=root --password='AlExAnDeRpWd' < ./sql.txt

########################################### Качаем звук ###########################################
FILE='asterisk-sound-ru-gsm'
$WGET $WGETOPT http://3090607.ru/asterisk/$FILE.tar.gz
echo "tar xvzf ./$FILE.tar.gz"
echo ""
echo ""
tar xvzf ./$FILE.tar.gz -C /var/lib/asterisk/sounds/
chown asterisk:asterisk -R /var/lib/asterisk/sounds/ru

cd $ROOT_DIR
$WGET $WGETOPT http://3090607.ru/asterisk/record.agi
chmod +x record.agi
mv -f record.agi /var/lib/asterisk/agi-bin/record.agi

cd $ROOT_DIR
$WGET $WGETOPT http://3090607.ru/asterisk/2wav2stereo.sh
chmod +x 2wav2stereo.sh
mv -f 2wav2stereo.sh /usr/local/bin/2wav2stereo.sh

FILE='wavplayer'
$WGET $WGETOPT http://3090607.ru/asterisk/$FILE.tar.gz
echo "tar xvzf ./$FILE.tar.gz"
tar xvzf ./$FILE.tar.gz -C /var/www/html
chown asterisk:asterisk -R /var/www/html/$FILE
cd $ROOT_DIR


mkdir /home/samba
mkdir /home/samba/records
ln -s /home/samba/records /var/www/html/records

############################################## ставим iptables ##############################################


echo "
#!/bin/sh

###########################################################################
#
# 1. Configuration options.
IPTABLES='/sbin/iptables'
LO_IFACE='lo'
#LAN_IFACE='eth0'
#INET_IFACE='eth1'
ADMIN_IP='195.64.140.121'

DNS1='8.8.8.8'
DNS2='8.8.4.4'

SIPPORT='5544'

#echo \"1\" > /proc/sys/net/ipv4/ip_forward

\$IPTABLES -F INPUT
\$IPTABLES -F OUTPUT
\$IPTABLES -F FORWARD

\$IPTABLES -t nat -F  PREROUTING
\$IPTABLES -t nat -F POSTROUTING

\$IPTABLES -P INPUT ACCPET
\$IPTABLES -P OUTPUT ACCEPT
\$IPTABLES -P FORWARD ACCEPT


\$IPTABLES -A INPUT -p all  -s \$ADMIN_IP -j ACCEPT
\$IPTABLES -A INPUT -p tcp -s ! \$LAN_NET  -m multiport --destination-port 25,53,80,110,111,139,323,445,979,3306,5038 -j DROP

#\$IPTABLES -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT

" > /etc/sysconfig/iptables


echo "
/etc/sysconfig/iptables
" >> /etc/rc.d/rc.local






