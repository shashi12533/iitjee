#!/bin/bash
curl -s --cookie "PHPSESSID=$2" "$4orsdisplay/orsdisplayp1ORS.php" -o pics/$3_1.html
curl -s --cookie "PHPSESSID=$2" "$4orsdisplay/orsdisplayp2ORS.php" -o pics/$3_2.html
