#!/bin/bash
curl -s -X POST -D- -d"appno=$1&formno=$2&DateOfBirth_Day=$3&DateOfBirth_Month=$4&DateOfBirth_Year=$5&submit=Submit"  "$6orsdisplay/index.php" -o/dev/null | grep PHPS | egrep -o "([a-z0-9]{26})"
