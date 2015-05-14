#!/bin/sh

result=${PWD}
export PATH=$PATH:$result/vendor/bin
#source /etc/bashrc

phploc src --log-xml phploc.xml
phpmd src xml rulesets/codesize.xml > pmd.xml
phpdox
phpcs --standard=PSR2 --extensions=php --report=csv --report-file=codesniffer_report.csv src/Diglin/
