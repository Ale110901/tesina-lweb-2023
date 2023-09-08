#!/bin/bash
MP_ES_OPT="-p - --font=Courier@11 --color=2"
MP_DEST="./codice/"

enscript ${MP_ES_OPT} ./www/lib/*.php | \
  ps2pdf -dPDFA - ${MP_DEST}/lib.pdf

enscript ${MP_ES_OPT} ./www/res/css/*.css | \
  ps2pdf -dPDFA - ${MP_DEST}/res_css.pdf

enscript ${MP_ES_OPT} --highlight=javascript ./www/res/js/*.js | \
  ps2pdf -dPDFA - ${MP_DEST}/res_js.pdf

enscript ${MP_ES_OPT} ./www/*.php ./www/{admin,cliente,gestore,utente}/*.php | \
  ps2pdf -dPDFA - ${MP_DEST}/pages.pdf

enscript ${MP_ES_OPT} ./www/data/*.{dtd,xsd} | \
  ps2pdf -dPDFA - ${MP_DEST}/xml.pdf

exit 0
