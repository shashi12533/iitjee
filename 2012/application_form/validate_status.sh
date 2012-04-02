#!/bin/bash
echo "14/24/25/15/11/21/16/26/TOTAL Remaining";
echo "SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C14%' )  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C24%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C25%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C15%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C11%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C21%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C16%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE (form_no LIKE 'C26%')  AND category IS NULL;
SELECT COUNT(*) FROM candidate WHERE category IS NULL;
" | mysql -uiitjee -pVTdT9ZhyVrVR8eza iitjee
