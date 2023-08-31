function aggiornaTipo() {
  var formOfferta = document.forms.offerta;
  var tipo = formOfferta.tipo.value;

  var elPercentuale = document.getElementById('input-percentuale');
  var elCrediti = document.getElementById('input-numCrediti');

  if (tipo === 'sconto') {
    elPercentuale.classList.remove('nascosto');
    elCrediti.classList.add('nascosto');
  } else if (tipo === 'bonus') {
    elPercentuale.classList.add('nascosto');
    elCrediti.classList.remove('nascosto');
  }
}

function aggiornaTarget() {
  var formOfferta = document.forms.offerta;
  var target = formOfferta.target.value;

  var elPercentuale = document.getElementById('input-percentuale');
  var elCrediti = document.getElementById('input-numCrediti');

  var elCreditiSpesi = document.getElementById('input-creditiSpesi');
  var elDataInizio = document.getElementById('input-dataInizio');
  var elReputazione = document.getElementById('input-reputazione');
  var elAnni = document.getElementById('input-anni');
  var elIdProdotto = document.getElementById('input-idProdotto');
  var elIdCategoria = document.getElementById('input-idCategoria');
  var elQuantitaMin = document.getElementById('input-quantitaMin');

  elCreditiSpesi.classList.add('nascosto');
  elDataInizio.classList.add('nascosto');
  elReputazione.classList.add('nascosto');
  elAnni.classList.add('nascosto');
  elIdProdotto.classList.add('nascosto');
  elIdCategoria.classList.add('nascosto');
  elQuantitaMin.classList.add('nascosto');

  switch (target) {
    case 'credData':
      elCreditiSpesi.classList.remove('nascosto');
      elDataInizio.classList.remove('nascosto');
      break;
    case 'credInizio':
      elCreditiSpesi.classList.remove('nascosto');
      break;
    case 'reputazione':
      elReputazione.classList.remove('nascosto');
      break;
    case 'dataReg':
      elAnni.classList.remove('nascosto');
      break;
    case 'prodSpec':
      elIdProdotto.classList.remove('nascosto');
      break;
    case 'categoria':
      elIdCategoria.classList.remove('nascosto');
      break;
    case 'eccMag':
      elIdProdotto.classList.remove('nascosto');
      elQuantitaMin.classList.remove('nascosto');
      break;
  }
}
