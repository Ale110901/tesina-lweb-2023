function mostraRecensioni() {
  var tabRec = document.getElementById('tab-rec');
  var tabDR = document.getElementById('tab-dr');
  var divRec = document.getElementById('recensioni');
  var divDR = document.getElementById('dr');

  tabDR.classList.add('tab-inattiva');
  tabDR.classList.remove('tab-attiva');

  tabRec.classList.add('tab-attiva');
  tabRec.classList.remove('tab-inattiva');

  divRec.classList.remove('nascosto');
  divDR.classList.add('nascosto');
}

function mostraDR() {
  var tabRec = document.getElementById('tab-rec');
  var tabDR = document.getElementById('tab-dr');
  var divRec = document.getElementById('recensioni');
  var divDR = document.getElementById('dr');

  tabRec.classList.add('tab-inattiva');
  tabRec.classList.remove('tab-attiva');

  tabDR.classList.add('tab-attiva');
  tabDR.classList.remove('tab-inattiva');

  divRec.classList.add('nascosto');
  divDR.classList.remove('nascosto');
}

function setCampo(prefisso, id, campo, valore) {
  var form = document.forms[prefisso + '_' + id];
  form.elements[campo].value = valore;

  var stelline = document.querySelectorAll('#' + campo + '_' + id + ' > a');
  for (var i = 0; i < stelline.length; i++) {
    if (i < valore) {
      stelline[i].textContent = '\u2605';
    } else {
      stelline[i].textContent = '\u2606';
    }
  }
}

function mostraAggiuntaRecensione(){
  document.getElementById('recensione_nuova').classList.toggle("nascosto");
}

function mostraAggiuntaDomande(){
  document.getElementById('domanda_nuova').classList.toggle("nascosto");
}

function mostraAggiuntaRisposta(id){
  document.getElementById('form-risposta-' + id).classList.toggle("nascosto");
}

function gestisciTextarea() {   /* NON FUNZIONA */
  var valoreRispostaTextarea = document.getElementById('valore_risposta');
  var inviaRispostaButton = document.getElementById('invia_risposta');
  var avvisoMessaggio = document.getElementById('messaggio');

  console.log(valoreRispostaTextarea.textContent);

  if (valoreRispostaTextarea.textContent.trim() === "") {
    avvisoMessaggio.classList.remove('nascosto');
  } else {
    inviaRispostaButton.disabled = false;
  }
}

function mostraRisposte(id) {
  document.getElementById('risp_' + id).classList.toggle("nascosto");
}
