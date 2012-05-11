#!/bin/bash
mkdir -p html
for i in {1..20}
do
	(./query.php $i) &
done
wait
