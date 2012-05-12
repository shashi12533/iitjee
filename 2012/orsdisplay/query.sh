#!/bin/bash
mkdir -p html
for i in {1..30}
do
	(./query.php $i) &
done
wait
