<?php require('lib/start.php'); ?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Prova &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="res/css/footer.css" />
</head>

<body>
  <?php require('lib/header.php'); ?>
  <div id="contenuto">
    <h2>CATALOGO</h2>
    <p>
Le FAQ (frequently asked questions) hanno una pagina apposita, accessibile da ogni tipologia di utente, anche visitatore. Visitatori e clienti possono solo visionarle, senza alcuna possibilità di modifica. Gestori e amministratori possono aggiungerle da zero, cliccando sul tasto “aggiungi” in cima alla pagina. In alternativa, dalla pagina di un prodotto, vicino a una domanda, trovano il tasto “eleva”. Cliccandolo, si sceglie la risposta da associare con una checkbox, se le risposte provengono solo da clienti, mentre se è presente la risposta “definitiva” di un gestore viene scelta automaticamente, ma è comunque possibile cambiare la selezione. Proseguendo, viene aperta la pagina di aggiunta di una nuova FAQ, precompilata con la suddetta domanda e risposta, per permetterne la modifica prima della pubblicazione.

Gli sconti e bonus vengono gestiti esclusivamente dai gestori. Il gestore trova una pagina apposita nella sua dashboard, chiamata “offerte”. All’interno trova una tabella con l’elenco degli sconti/bonus definiti. Al lato di ogni riga, è possibile eliminare l’offerta. Inoltre, in fondo alla tabella, si trovano i campi per l’inserimento di una nuova voce.
Gli sconti saranno visibili direttamente nella griglia del catalogo, mentre i bonus all’interno della pagina dedicata al prodotto. Un visitatore vedrà solo gli sconti/bonus “generali”, cioè non applicati ad una specifica categoria di cliente, mentre un cliente vede sconti/bonus generali ma anche quelli che si applicano ad esso.
Il resoconto degli sconti/bonus applicati sarà disponibile nel carrello.

I clienti/visitatori hanno un accesso in sola lettura ai prodotti in catalogo, cioè possono solo visualizzarli e acquistarli. I gestori hanno una pagina nella loro dashboard con una tabella con un resoconto dei prodotti, tramite la quale possono modificarli, aprendo una pagina di modifica, ed eliminarli ed inoltre con un bottone alla fine della pagina possono aggiungerne di nuovi. Le quantità disponibili in stock possono essere modificate direttamente dalla tabella.
Inoltre, quando viene finalizzato un acquisto, viene decrementata la quantità acquistata dai prodotti corrispondenti.
Se il prodotto ha quantità zero, cioè non è disponibile in magazzino, al gestore viene segnalato nella sua tabella prodotti da un’icona, mentre ai clienti/visitatori il tasto “aggiungi al carrello” non sarà disponibile e sarà presente un messaggio di prodotto “out-of-stock”.
La tesina verte sulla gestione di un negozio online di rivendita prodotti per la palestra (proteine, barrette proteiche, ecc…) offrendo un catalogo multimarca.

E’ presente un catalogo di prodotti, descritti con testo e immagini, che tutti gli utenti possono consultare, registrati e visitatori.
Il catalogo puo' essere visionato secondo diversi ordinamenti (costo, categoria e marca del prodotto) e si possono escludere i prodotti “out-of-stock” dalla lista.
Categorie
Ad un prodotto viene associata una categoria specifica, ad esempio di default sono definite:
Proteine in polvere
Barrette proteiche
Vitamine
Abbigliamento
Accessori
All’occorrenza il gestore può definire nuove categorie da una pagina apposita.
Offerte
Un elemento del catalogo puo' prevedere un’offerta.
Le offerte possono essere sconti o bonus.
Uno sconto è una percentuale di riduzione del prezzo dell’articolo (es. 20%), mentre con il bonus si ha una quantità fissata che viene restituita al cliente sotto forma di crediti (es. +3 crediti).

Le offerte hanno un target: sono previste “per tutti” (es. per tutti gli utenti, le proteine sono scontate del 10%), oppure personalizzate per una categoria di cliente (es. per i clienti da più di 2 anni tutti gli articoli sono scontati del 10%).

Nel caso dell’offerta personalizzata alla singola categoria di cliente, viene applicata a tutti i prodotti in catalogo e in base ai seguenti criteri:
clienti che hanno speso X crediti finora
clienti che hanno speso X crediti da una certa data Y
clienti che hanno una reputazione >= X
clienti che sono con noi da X anni

Nel caso dello sconto sul prodotto in sé, nel catalogo, viene applicato a tutti i clienti se:
è un prodotto particolare, scelto dal gestore al momento di definizione dell’offerta
il prodotto è di una determinata categoria
è presente in magazzino un’eccedenza del prodotto X (più di Y unità) [spiegato successivamente]

Uno sconto puo' essere aggiunto nel novero degli sconti, o eliminato, dal gestore o dall'amministratore.
Magazzino
Il sistema tiene traccia delle quantità dei singoli prodotti disponibili in magazzino.
Con l’acquisto dei prodotti essa viene decrementata automaticamente. Se arriva a zero, il prodotto viene contrassegnato nella pagina del catalogo come “out-of-stock” e non è possibile il suo acquisto.
Inoltre, la quantità dei singoli prodotti può essere modificata manualmente dal gestore, ad esempio quando i prodotti arrivano man mano dai fornitori.
Questa è la quantità controllata dal sistema per verificare se un’offerta del tipo “eccedenza di prodotto” è applicabile o meno.
Crediti
Un cliente acquisisce crediti da spendere facendo una richiesta all'admin, attraverso una funzionalità del sistema.
L'admin vaglia la richiesta, controllando se è stato corrisposto il pagamento in modo “offline”, cioè in modo separato da questa applicazione, disponendo di un bottone per accettarla o rifiutarla.
Una volta accettata, la quantità verrà assegnata all’utente.
Recensioni, domande e risposte
Il sistema permette di scrivere, riguardo ai prodotti in catalogo, recensioni, fare domande, dare risposte.
I clienti possono giudicare i post suddetti, assegnando loro un valore di "supporto" ("accordo", da 1 a 3) e uno di "utilità" ("è stato utile da leggere", da 1 a 5).
FAQ
Esiste una pagina di FAQ, gestita dall'admin.
Una domanda postata da clienti, con una relativa risposta, può essere "elevata" a far parte delle FAQ.
    </p>
    <!--

    <div id="catalogo-shop">
      <div id="articolo_ID">
        <img src="res/css/shop_img/shop_ID.png"  alt="shop_ID.png" ></img>
        <p>NOME</p>
        <p class="prezzo">PREZZO &euro;</p>
        <form class="pt-1em" action="shop.php#articolo_ID" method="post">
          <input type="hidden" name="id_articolo" value="ID" />
          <input type="number" name="quantita" value="0" min="0" step="1" size="3" max="99" />
          <button type="submit" name="azione" value="aggiungi" class="button ml-8">Aggiungi</button>
        </form>
      </div>
    </div>

    <div class="centrato pt-64">
      <a class="button" href="carrello.php">Vai al carrello</a>
    </div>
  -->
  </div>
  <?php require('lib/footer.php'); ?>
</body>

</html>
