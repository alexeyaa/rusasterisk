#!/bin/sh
######################################################################################################
######################################################################################################
#Скрипт устанавливает нам Астериск
######################################################################################################
######################################################################################################
######################################################################################################
######################################################################################################
HW='i386'
#HW='x86_64'
VERSION="0.0.3"
ROOT_DIR=`pwd`

clear

IFCONFIG=`which ifconfig 2>/dev/null||echo /sbin/ifconfig`
IPADDR=`$IFCONFIG |gawk '/inet addr/{print $2}'|gawk -F: '{print $2}'`
echo "$IPADDR"

echo "Версия скрипта $VERSION"
echo "Железка $HW"
echo "Жмем CTRL-C для выхода, или начинаем ставить дальше"
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
echo "######################### yum install make ##################################"
yum -y install make.$HW
echo "######################### yum install mc  ##################################"
yum -y install mc.$HW
echo "######################### yum install yum-repos-asterisk  ##################################"
yum -y install yum-repos-asterisk.noarch
echo "######################### yum install asterisk 16 ##################################"
yum -y install asterisk16.$HW 
echo "######################### yum install sox  ##################################"
yum -y install sox.$HW


##################################### Ставим perl AGI #####################################

cd $ROOT_DIR/config_asterisk/asterisk-perl-1.01
perl Makefile.PL
make all
make install
cd $ROOT_DIR

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

##################################### ставим MySQL #####################################

yum -y install mysql-server.$HW
service mysqld start
/usr/bin/mysqladmin -u root password 'AlExAnDeRpWd'
mysql --user=root --password='AlExAnDeRpWd' < $ROOT_DIR/config_asterisk/sql.txt

##################### после mysql ставим addon астериска
yum -y install asterisk16-addons.$HW 
yum -y install asterisk16-addons-mysql.$HW
yum -y install asterisk16-configs.$HW

########################################### разбираемся со  звуком ###########################################

mv -f $ROOT_DIR/config_asterisk/sound/sound/*  /var/lib/asterisk/sounds/
chown asterisk:asterisk -R /var/lib/asterisk/sounds/ru

mv -f $ROOT_DIR/config_asterisk/agi/record.agi /var/lib/asterisk/agi-bin/record.agi

mv -f $ROOT_DIR/config_asterisk/sound/2wav2stereo.sh /usr/local/bin/2wav2stereo.sh

mkdir /var/www/html/wavplayer
mv -f $ROOT_DIR/config_asterisk/sound/wavplayer/*  /var/www/html/wavplayer/
chown asterisk:asterisk -R /var/www/html/$FILE

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

