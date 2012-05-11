#!/bin/bash
for file in html/1*_1.html
do
    echo $file
	php marks_parser.php $file
done
