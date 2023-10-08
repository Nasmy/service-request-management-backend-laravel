#Test comment
echo "Deploy script started"
cd /var/www/crsdb.com/html/srm-backend
mv composer.lock composer.lock_old
sh pull.sh
echo "Deploy script finished execution"
