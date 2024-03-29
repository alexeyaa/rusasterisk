#!/bin/sh
######################################################################################################
######################################################################################################
################# #Скрипт устанавливает нам Астериск
######################################################################################################
######################################################################################################
######################################################################################################
######################################################################################################
HW='i386'
#HW='x86_64'
VERSION="0.1.0"
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
echo "################################################ подправили DNS ##################################"
echo "
nameserver 8.8.8.8
nameserver 8.8.4.4
" > /etc/resolv.conf

###################### Скачиваем и ставим Asterisk начинаем с репозиториев  ############################
echo "###################### Скачиваем и ставим Asterisk начинаем с репозиториев  ######################"
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

echo "############################# yum install make ####################################################"
yum -y install make
echo "############################# yum install mc  #####################################################"
yum -y install mc
echo "#################### yum install yum-repos-asterisk  ##############################################"
yum -y install yum-repos-asterisk.noarch
echo "############################ yum install asterisk 16 ##############################################"
yum -y install asterisk16
#################### что б астериск работал качественно редактируем safe_asterisk
sed -i -e 's/^TTY=/#TTY=/g' /usr/sbin/safe_asterisk

########################## ставим MOH и звуки  ###########################################################
echo "########################## ставим MOH и звуки  #####################################################"

yum -y install asterisk-sounds-moh-opsound-ulaw.noarch
yum -y install asterisk-sounds-core-en-gsm.noarch
echo "################################# yum install sox  #################################################"
yum -y install sox

echo "################################## yum install php  ################################################"
yum -y remove php
yum -y remove php-common

yum -y install php-mysql
yum -y install php
yum -y install php-gd

echo "Жмем CTRL-C если не поставился php или продолжаем дальше ставить"
read TEMP

#################################### ставим статистику, телефонную книгу  ###############################
sed -i -e 's/register_globals = Off/register_globals = On/g' /etc/php.ini
cp -f -R $ROOT_DIR/config_asterisk/www/  /var/www/html/

mkdir -p /usr/X11R6/lib/X11/fonts/TTF
cp -f -R $ROOT_DIR/config_asterisk/fonts/  /usr/X11R6/lib/X11/fonts/TTF

############################################# Ставим perl AGI ###########################################
echo "############################################## Ставим perl AGI ####################################"
cd $ROOT_DIR/config_asterisk/asterisk-perl-1.01
perl Makefile.PL
make all
make install
cd $ROOT_DIR

########################################### Скачиваем и ставим Apache  ###################################
echo "################################### yum install httpd  #############################################"
yum -y install httpd
echo "
Listen 323
Listen 324
Listen 325
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

<VirtualHost *:324>
 ServerName asterisk.localhost
 ServerAlias *.asterisk.localhost
 ServerAdmin $email
 ErrorLog /var/log/httpd/asterisk.err
 CustomLog /var/log/httpd/asterisk.log combined
 DocumentRoot /var/www/html/phonebook/
 <Directory \"/var/www/html/phonebook/\">
   DirectoryIndex index.php index.html
   Options FollowSymLinks +Indexes
   Order allow,deny
   Allow from all
 </Directory>
</VirtualHost>

<VirtualHost *:325>
 ServerName asterisk.localhost
 ServerAlias *.asterisk.localhost
 ServerAdmin 101@3090607.ru
 ErrorLog /var/log/httpd/stat.err
 CustomLog /var/log/httpd/stat.log combined
 DocumentRoot /var/www/html/stat/
  <Directory \"/var/www/html/stat/\">
   DirectoryIndex index.php index.html
   Options FollowSymLinks +Indexes
   Order allow,deny
   Allow from all
  </Directory>
</VirtualHost>
                    
" > /etc/httpd/conf.d/asterisk.conf

echo "Hellow World" > /var/www/html/index.html

############################################# ставим MySQL #############################################
echo "################################# yum install mysql  #############################################"
yum -y install mysql-server
service mysqld start
/usr/bin/mysqladmin -u root password "$PASSWD"
mysql --user=root --password="$PASSWD" < $ROOT_DIR/config_asterisk/sql.txt
mysql --user=root --password="$PASSWD" < $ROOT_DIR/config_asterisk/phonebook.txt
########################################## python-mysql для pbook ######################################
yum -y  install MySQL-python

########################### после mysql ставим addon астериска  ########################################
echo "######################### после mysql ставим addon астериска  ####################################"
yum -y install asterisk16-addons 
yum -y install asterisk16-addons-mysql
yum -y install asterisk16-configs

########################################## инсталируем прочую требуху ##################################
echo "##################################### инсталируем прочую требуху #################################";
yum -y install nmap

############################################# Hylafax #######################################
yum -y install gcc
yum -y install gcc-c++
yum -y install libtiff-devel
yum -y localinstall --nogpgcheck  ./hylafax-5.5.0-1.i386.rpm


################################### копируем рабочие конфиги и файлы####################################
echo "##################################################################################################"
echo "##################################################################################################"
echo "##################################################################################################"
echo "################################## копируем рабочие конфиги и файлы ##############################"
echo "##################################################################################################"
echo "##################################################################################################"
echo "##################################################################################################"
echo "##################################################################################################"
cp -f -R $ROOT_DIR/config_asterisk/sound/sound/*  /var/lib/asterisk/sounds/
chown asterisk:asterisk -R /var/lib/asterisk/sounds/ru

cp -f $ROOT_DIR/config_asterisk/agi/* /var/lib/asterisk/agi-bin/
chown asterisk:asterisk -R /var/lib/asterisk/agi-bin/

cp -f $ROOT_DIR/config_asterisk/sound/2wav2stereo.sh /usr/local/bin/2wav2stereo.sh
chown asterisk:asterisk /usr/local/bin/2wav2stereo.sh
chmod +x /usr/local/bin/2wav2stereo.sh

mkdir /var/www/html/wavplayer
cp -f -R $ROOT_DIR/config_asterisk/sound/wavplayer/*  /var/www/html/wavplayer/
chown asterisk:asterisk -R /var/www/html

cp -f -R $ROOT_DIR/config_asterisk/sound/moh/*  /var/lib/asterisk/moh
chown asterisk:asterisk -R /var/lib/asterisk/moh/

cp -f $ROOT_DIR/config_asterisk/call_mf.pl  /var/lib/asterisk/call.pl
chown asterisk:asterisk /var/lib/asterisk/call.pl
chmod +x /var/lib/asterisk/call.pl

cp -f $ROOT_DIR/config_asterisk/phone.txt  /var/lib/asterisk/phone.txt
chown asterisk:asterisk /var/lib/asterisk/phone.txt

 
cp -f $ROOT_DIR/config_asterisk/app_voicechanger.so  /usr/lib/asterisk/modules/
chown asterisk:asterisk /usr/lib/asterisk/modules/app_voicechanger.so


mkdir /home/samba
mkdir /home/samba/records
ln -s /home/samba/records /var/www/html/records

####################### добавляем перловский скрипт для отправки почты ################################
cp -f -R $ROOT_DIR/config_asterisk/sendEmail /bin/sendEmail
chmod +x /bin/sendEmail

######################################### ставим iptables #############################################
cp -f $ROOT_DIR/iptables /etc/sysconfig/iptables
chmod +x $ROOT_DIR/iptables
echo "
/etc/sysconfig/iptables
" >> /etc/rc.d/rc.local
####################################### запускаем iptables ############################################
echo "################################### запускаем iptables ##########################################"
/etc/sysconfig/iptables
#################################### Меняем временную зону ############################################
cp /etc/localtime /etc/localtime_old
rm -f /etc/localtime
cp -f /usr/share/zoneinfo/Etc/GMT-4 /etc/localtime

######################################### консоль подправляем #########################################
#echo "PS1='\[\033[01;31m\]>>\[\033[00m\] \! \u \[\033[01;34m\]\w\[\033[00m\] \n  \[\033[01;31m\].\[\033[00m\]'"  >> /root/.bashrc


######################## и напоследок запускаем mc, что бы появился .mc директория ####################
mkdir /root/.mc
echo "
ENTRY \"/etc/asterisk\" URL \"/etc/asterisk\"
ENTRY \"/var/lib/asterisk\" URL \"/var/lib/asterisk\"
ENTRY \"/var/spool/asterisk/outgoing\" URL \"/var/spool/asterisk/outgoing\"
ENTRY \"/usr/local/src\" URL \"/usr/local/src\"
ENTRY \"/root\" URL \"/root\"
ENTRY \"/home\" URL \"/home\"
" > /root/.mc/hotlist

#################################### перегружаем сервисы ###############################################
echo "##################################################################################################"
echo "##################################################################################################"
echo "##################################################################################################"
echo "################################## перегружаем сервисы ###########################################"
echo "##################################################################################################"
echo "##################################################################################################"
echo "##################################################################################################"
echo "##################################################################################################"
service httpd restart

######################################### консоль подправляем ##########################################
#echo "PS1='\[\033[01;31m\]>>\[\033[00m\] \! \u \[\033[01;34m\]\w\[\033[00m\] \n  \[\033[01;31m\].\[\033[00m\]'"  >> /root/.bashrc


