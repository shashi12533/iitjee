#!/bin/bash
for year in 94 95 93
do
	for month in {1..12}
	do
	    for day in {1..31}
	    do
	    	#echo $day/$month/$year
            month_f=`printf "%02d" $month`
            day_f=`printf "%02d" $day`
            echo "INSERT INTO dates VALUES('$day_f/$month_f/$year');"
	    done
	done
done