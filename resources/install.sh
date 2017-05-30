touch /tmp/dependancy_panasonicVIERA_in_progress
echo 0 > /tmp/dependancy_panasonicVIERA_in_progress
echo "Launch install of PanasonicViera dependancy"
cd /tmp
rm -R /tmp/panasonic-viera >/dev/null 2>&1
git clone https://github.com/Turgon37/panasonic-viera.git
echo 50 > /tmp/dependancy_panasonicVIERA_in_progress
cd /tmp/panasonic-viera
sudo python setup.py install
echo 100 > /tmp/dependancy_panasonicVIERA_in_progress
echo "Everything is successfully installed!"
rm /tmp/dependancy_panasonicVIERA_in_progress
