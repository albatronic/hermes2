#!/bin/sh

touch /home/albatro/public_html/Hermes/log/escaparates.log

echo "Regeneracion de escaparates `date`" >> /home/albatro/public_html/Hermes/log/escaparates.log
echo "------------------------------------------------------------" >> /home/albatro/public_html/Hermes/log/escaparates.log

php regeneraEscaparates.php
