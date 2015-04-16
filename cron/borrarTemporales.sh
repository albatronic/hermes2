#!/bin/sh

touch /home/albatro/public_html/Hermes/cron/log.txt

echo "Borrado de archivos temporales `date`" >> /home/albatro/public_html/Hermes/cron/log.txt
echo "------------------------------------------------------------" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs1/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs1/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs1/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs1/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs1/yml/*
echo "Temporales docs1" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs2/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs2/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs2/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs2/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs2/yml/*
echo "Temporales docs2" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs3/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs3/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs3/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs3/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs3/yml/*
echo "Temporales docs3" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs4/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs4/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs4/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs4/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs4/yml/*
echo "Temporales docs4" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs5/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs5/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs5/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs5/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs5/yml/*
echo "Temporales docs5" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs6/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs6/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs6/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs6/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs6/yml/*
echo "Temporales docs6" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs7/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs7/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs7/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs7/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs7/yml/*
echo "Temporales docs7" >> /home/albatro/public_html/Hermes/cron/log.txt

rm -Rf /home/albatro/public_html/Hermes/docs/docs11/pdfs/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs11/tmp/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs11/xls/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs11/xml/*
rm -Rf /home/albatro/public_html/Hermes/docs/docs11/yml/*
echo "Temporales docs11" >> /home/albatro/public_html/Hermes/cron/log.txt

echo " " >> /home/albatro/public_html/Hermes/cron/log.txt