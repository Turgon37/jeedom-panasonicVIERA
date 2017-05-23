touch /tmp/dependancy_panasonicVIERA_in_progress
echo 0 > /tmp/dependancy_panasonicVIERA_in_progress
echo "Launch install of Networks dependancy"
sudo apt-get update
echo 50 > /tmp/dependancy_panasonicVIERA_in_progress
sudo apt-get install -y wakeonlan
echo 100 > /tmp/dependancy_panasonicVIERA_in_progress
echo "Everything is successfully installed!"
rm /tmp/dependancy_panasonicVIERA_in_progress
