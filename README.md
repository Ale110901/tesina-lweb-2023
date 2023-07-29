# tesina-lweb-2023

### Tesina per il corso di Linguaggi Web 2023

## Componenti
- ```Ale110901```: Alessandro Cecchetto - 1941039
  - link: https://github.com/Ale110901/tesina-lweb-2023.git
- ```Hackfront-ITA```: Emanuele Roccia - 1967318
  - link: https://github.com/Hackfront-ITA/tesina-lweb-2023.git

## Installazione
- Copiare il contenuto della cartella `www` nella cartella di destinazione.
- Modificare il file `config.php` con le proprie impostazioni.

Per esempio:
- la root del web server e' `/srv/www/`
- l'indirizzo di destinazione e' `http://10.173.0.1/rc-store/`

Allora si copia il contenuto di `www` in `/srv/www/rc-store/` e nel file
`config.php` si imposta:
- la costante `RC_ROOT` a `/srv/www/rc-store` (senza `/` finale)
- la costante `RC_SUBDIR` a `/rc-store` (senza `/` finale)

:snowman:
