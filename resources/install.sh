touch /tmp/dependancy_panasonicVIERA_in_progress
echo "Launch install of PanasonicViera dependancy"
echo 0 > /tmp/dependancy_panasonicVIERA_in_progress
echo 'Configure 3rdParty'
chmod +x `dirname $0`/../3rdparty/panasonic_viera_adapter.py

echo 10 > /tmp/dependancy_panasonicVIERA_in_progress
echo 'Download panasonic-viera library'
cd /tmp
sudo rm -R /tmp/panasonic-viera >/dev/null 2>&1
git clone https://github.com/Turgon37/panasonic-viera.git

echo 50 > /tmp/dependancy_panasonicVIERA_in_progress
echo 'Install panasonic-viera library'
cd /tmp/panasonic-viera
echo 'y' | sudo pip --quiet uninstall panasonic_viera
sudo python setup.py install

echo 95 > /tmp/dependancy_panasonicVIERA_in_progress
echo 'Cleaning...'
sudo rm -R /tmp/panasonic-viera >/dev/null 2>&1

echo 100 > /tmp/dependancy_panasonicVIERA_in_progress
echo "Everything is successfully installed!"
rm /tmp/dependancy_panasonicVIERA_in_progress
