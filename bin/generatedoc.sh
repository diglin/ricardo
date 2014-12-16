#!/bin/sh

## todo: improve with getting the script from environment
phploc.phar src --log-xml phploc.xml
phpmd.phar src xml rulesets/codesize.xml >> pmd.xml
phpdox
