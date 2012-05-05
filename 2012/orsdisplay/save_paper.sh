#!/bin/bash
curl -s --cookie "PHPSESSID=$2" "$4orsdisplay/orsdisplayp1.php" -o html/$3_1.html
curl -s --cookie "PHPSESSID=$2" "$4orsdisplay/orsdisplayp2.php" -o html/$3_2.html

