// Stimulus controllers
import './stimulus_bootstrap.js';

// Bootstrap JS (dropdown, modal, navbar toggler)
import 'bootstrap';

import 'bootstrap-icons/font/bootstrap-icons.css';

// Własne style (zawierają pełny Bootstrap SCSS + nadpisania)
import './styles/app.scss';

document.addEventListener('DOMContentLoaded', () => {
  const alerts = document.querySelectorAll('.alert');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.classList.remove('show');
      alert.classList.add('hide');
      setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
      }, 600); // czas trwania animacji fadeOut
    }, 5000); // 5 sekund
  });
});
