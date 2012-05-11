#!/bin/bash
curl --cookie "PHPSESSID=$1" "$3"orsdisplay/orsdisplayp1.php -o html/$2_1.html
curl --cookie "PHPSESSID=$1" "$3"orsdisplay/orsdisplayp2.php -o html/$2_2.html
