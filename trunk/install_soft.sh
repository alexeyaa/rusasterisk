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
VERSION="0.0.4"
ROOT_DIR=`pwd`
PASSWD='AlExAnDeRpWd'
email='101@3090607.ru'
clear

IFCONFIG=`which ifconfig 2>/dev/null||echo /sbin/ifconfig`
IPADDR=`$IFCONFIG |gawk '/inet addr/{print $2}'|gawk -F: '{print $2}' | grep -v 127.0`
echo "$IPADDR"

echo "Версия скрипта $VERSION"
echo "Железка $HW"
echo "Жмем CTRL-C для выхода, или начинаем ставить дальше"
read TEMP

################################################ подправили DNS ########################################
echo "
nameserver 8.8.8.8
nameserver 8.8.4.4
" > /etc/resolv.conf


###################### Скачиваем и ставим Asterisk начинаем с репозиториев  ############################
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
echo "######################### yum install mc  ###################################"
yum -y install mc.$HW
echo "#################### yum install yum-repos-asterisk  ########################"
yum -y install yum-repos-asterisk.noarch
echo "######################### yum install asterisk 16 ###########################"
yum -y install asterisk16.$HW 
#################### что б астериск работал качественно редактируем safe_asterisk
sed -i -e  's/^TTY=/#TTY=/g' /usr/sbin/safe_asterisk

service asterisk restart

########################## ставим MOH ############################################
yum -y install asterisk-sounds-moh-opsound-ulaw

echo "######################### yum install sox  ##################################"
yum -y install sox.$HW

echo "######################### yum install php  ##################################"
yum install php.x86_64



##################################### Ставим perl AGI #####################################
echo "######################### ставим PerlAGI  ##################################"
cd $ROOT_DIR/config_asterisk/asterisk-perl-1.01
perl Makefile.PL
make all
make install
cd $ROOT_DIR

##################################### Скачиваем и ставим Apache  ###################################
echo "######################### yum install httpd  ##################################"
yum -y install httpd.$HW
echo "
Listen 323
<VirtualHost *:323>
 ServerName asterisk.localhost
 ServerAlias *.asterisk.localhost
 ServerAdmin $email
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
echo "######################### yum install mysql  ##################################"
yum -y install mysql-server.$HW
service mysqld start
/usr/bin/mysqladmin -u root password "$PASSWD"
mysql --user=root --password="$PASSWD" < $ROOT_DIR/config_asterisk/sql.txt

##################### после mysql ставим addon астериска
yum -y install asterisk16-addons.$HW 
yum -y install asterisk16-addons-mysql.$HW
yum -y install asterisk16-configs.$HW

########################################### разбираемся со  звуком ###########################################

cp -f -R $ROOT_DIR/config_asterisk/sound/sound/*  /var/lib/asterisk/sounds/
chown asterisk:asterisk -R /var/lib/asterisk/sounds/ru

cp -f $ROOT_DIR/config_asterisk/agi/record.agi /var/lib/asterisk/agi-bin/record.agi

cp -f $ROOT_DIR/config_asterisk/sound/2wav2stereo.sh /usr/local/bin/2wav2stereo.sh

mkdir /var/www/html/wavplayer
cp -f -R $ROOT_DIR/config_asterisk/sound/wavplayer/*  /var/www/html/wavplayer/
chown asterisk:asterisk -R /var/www/html/$FILE

cp -f -R $ROOT_DIR/config_asterisk/sound/moh/*  /var/lib/asterisk/moh
chown asterisk:asterisk -R /var/lib/asterisk/moh/

mkdir /home/samba
mkdir /home/samba/records
ln -s /home/samba/records /var/www/html/records

################################## добавляем перловский скрипт для отправки почты ##################################
cp -f -R $ROOT_DIR/config_asterisk/sendEmail /bin/sendEmail

############################################## ставим iptables ##############################################
cp -f $ROOT_DIR/iptables /etc/sysconfig/iptables
chmod +x $ROOT_DIR/iptables
echo "
/etc/sysconfig/iptables
" >> /etc/rc.d/rc.local

################### перегружаем сервисы
service httpd restart

########################### консоль подправляем ####################################
echo "PS1='\[\033[01;31m\]>>\[\033[00m\] \! \u \[\033[01;34m\]\w\[\033[00m\] \n  \[\033[01;31m\].\[\033[00m\]'"  >> /root/.bashrc
echo "
ENTRY \"/etc/asterisk\" URL \"/etc/asterisk\"
ENTRY \"/var/lib/asterisk\" URL \"/var/lib/asterisk\"
ENTRY \"/var/spool/asterisk/outgoing\" URL \"/var/spool/asterisk/outgoing\"
ENTRY \"/usr/local/src\" URL \"/usr/local/src\"
ENTRY \"/root\" URL \"/root\"
ENTRY \"/home\" URL \"/home\"
"
>> /root/.mc/hotlist

