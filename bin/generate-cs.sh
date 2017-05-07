#!/bin/bash
bin/phpcs -v -p --colors --report-file=public/phpcs/full.html --report=full
bin/phpcs -v -p --colors --report-file=public/phpcs/summary.html --report=summary