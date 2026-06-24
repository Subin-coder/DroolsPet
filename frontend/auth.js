// Shared auth helpers for login/register pages

(function () {
  // Try to keep UI in sync immediately on page load
  document.addEventListener('DOMContentLoaded', () => {
    try {
      if (window.UIHelper?.updateHeader) UIHelper.updateHeader();
      if (window.UIHelper?.updateCartCount) UIHelper.updateCartCount();

      // Pre-fill email if remember me was checked
      const rememberedEmail = localStorage.getItem('remembered_email');
      if (rememberedEmail) {
        const emailInput = getEl('email');
        if (emailInput) {
          emailInput.value = rememberedEmail;
          const rememberCheckbox = getEl('remember');
          if (rememberCheckbox) {
            rememberCheckbox.checked = true;
          }
        }
      }
    } catch (e) {
      // no-op
    }
  });
  function getEl(id) {
    return document.getElementById(id);
  }

  function safeTrim(v) {
    return (v ?? '').toString().trim();
  }

  function extractError(resp) {
    // APIService.request wraps backend JSON as resp.data
    const data = resp?.data;
    if (!data) return 'Request failed';

    if (data?.error) return data.error;

    if (data?.errors && typeof data.errors === 'object') {
      const parts = Object.values(data.errors).flat().filter(Boolean);
      if (parts.length) return parts.join(', ');
    }

    return 'Request failed';
  }

  function setLoading(btn, text) {
    if (!btn) return;
    btn.disabled = true;
    btn.dataset.originalText = btn.dataset.originalText || btn.textContent;
    btn.textContent = text;
  }

  function unsetLoading(btn) {
    if (!btn) return;
    btn.disabled = false;
    const original = btn.dataset.originalText;
    if (original) btn.textContent = original;
  }

  async function handleLogin(event) {
    event.preventDefault();

    const submitBtn = getEl('login-submit');
    setLoading(submitBtn, 'Logging in...');

    try {
      const email = safeTrim(getEl('email')?.value);
      const password = getEl('password')?.value ?? '';
      const rememberMe = getEl('remember')?.checked;

      const response = await APIService.login(email, password);

      if (response.status === 200 && response.data?.token) {
        const data = response.data;
        APIService.setAuthToken(data.token);
        APIService.setCurrentUser(data.user);

        // Save email if remember me is checked
        if (rememberMe) {
          localStorage.setItem('remembered_email', email);
        } else {
          localStorage.removeItem('remembered_email');
        }

        UIHelper.showAlert('Login successful!');

        setTimeout(() => {
          const role = data?.user?.role;
          if (role === 'admin') window.location.href = 'admin-dashboard.html';
          else window.location.href = 'index.html';
        }, 900);
        return;
      }

      UIHelper.showError(extractError(response) || 'Login failed');
    } finally {
      unsetLoading(submitBtn);
    }
  }

  async function handleRegister(event) {
    event.preventDefault();

    const submitBtn = getEl('register-submit');
    setLoading(submitBtn, 'Creating account...');

    try {
      const full_name = safeTrim(getEl('full_name')?.value);
      const email = safeTrim(getEl('email')?.value);
      const password = getEl('password')?.value ?? '';
      const confirm_password = getEl('confirm_password')?.value ?? '';

      if (!full_name) {
        UIHelper.showError('Full name is required');
        return;
      }
      if (!email) {
        UIHelper.showError('Email is required');
        return;
      }
      if (!password || password.length < 6) {
        UIHelper.showError('Password must be at least 6 characters');
        return;
      }

      if (password !== confirm_password) {
        UIHelper.showError('Passwords do not match');
        return;
      }

      const username = full_name; // backend accepts username; we store full name as username

      const response = await APIService.register(username, email, password, full_name);

      if ((response.status === 200 || response.status === 201) && response.data?.token) {
        const data = response.data;
        APIService.setAuthToken(data.token);
        APIService.setCurrentUser(data.user);
        UIHelper.showAlert('Registration successful! Welcome!');

        setTimeout(() => {
          window.location.href = 'index.html';
        }, 900);
        return;
      }

      UIHelper.showError(extractError(response) || 'Registration failed');
    } finally {
      unsetLoading(submitBtn);
    }
  }

  // expose globals for inline onsubmit handlers
  window.handleLogin = handleLogin;
  window.handleRegister = handleRegister;
})();

