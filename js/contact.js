/**
 * contact.js — Verdana Studio
 * Client-side validation for the contact form.
 */

document.addEventListener('DOMContentLoaded', () => {

  const form      = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  if (!form) return;

  const fields = {
    name:    document.getElementById('name'),
    email:   document.getElementById('email'),
    phone:   document.getElementById('phone'),
    service: document.getElementById('service'),
    message: document.getElementById('message'),
  };

  /* --- Validators --- */
  const validators = {
    name:    v => !v.trim() ? 'Your name is required.' : v.trim().length < 2 ? 'Name must be at least 2 characters.' : null,
    email:   v => !v.trim() ? 'Your email address is required.' : !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ? 'Please enter a valid email address.' : null,
    phone:   v => v && !/^[0-9\+\-\s\(\)]{7,15}$/.test(v) ? 'Please enter a valid phone number.' : null,
    service: v => !v ? 'Please select a service.' : null,
    message: v => !v.trim() ? 'Please write a message.' : v.trim().length < 20 ? 'Message must be at least 20 characters.' : null,
  };

  /* --- Show / clear errors --- */
  const showError = (key, msg) => {
    const group = fields[key]?.closest('.form-group');
    if (!group) return;
    group.classList.add('has-error');
    group.querySelector('.field-error')?.remove();
    const span = document.createElement('span');
    span.className = 'field-error';
    span.textContent = msg;
    group.appendChild(span);
  };

  const clearError = key => {
    const group = fields[key]?.closest('.form-group');
    if (!group) return;
    group.classList.remove('has-error');
    group.querySelector('.field-error')?.remove();
  };

  /* --- Real-time validation on blur --- */
  Object.keys(fields).forEach(key => {
    const el = fields[key];
    if (!el) return;
    el.addEventListener('blur',   () => { const e = validators[key](el.value); e ? showError(key, e) : clearError(key); });
    el.addEventListener('input',  () => clearError(key));
    el.addEventListener('change', () => clearError(key));
  });

  /* --- Character counter on message --- */
  const msg = fields.message;
  if (msg) {
    const counter = document.createElement('span');
    counter.style.cssText = 'display:block;text-align:right;font-size:0.75rem;color:var(--muted);margin-top:4px;';
    counter.textContent = '0 / 1000';
    msg.closest('.form-group').appendChild(counter);
    msg.addEventListener('input', () => {
      const len = msg.value.length;
      counter.textContent = `${len} / 1000`;
      counter.style.color = len > 950 ? '#e24b4a' : 'var(--muted)';
      if (len > 1000) msg.value = msg.value.slice(0, 1000);
    });
  }

  /* --- Submit: full validation --- */
  form.addEventListener('submit', e => {
    let valid = true;
    Object.keys(validators).forEach(key => {
      const el  = fields[key]; if (!el) return;
      const err = validators[key](el.value);
      err ? (showError(key, err), valid = false) : clearError(key);
    });

    if (!valid) {
      e.preventDefault();
      form.querySelector('.has-error')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    // Honeypot check
    const hp = form.querySelector('input[name="website"]');
    if (hp?.value.trim()) { e.preventDefault(); return; }

    // Loading state
    if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending…'; }
  });

  /* --- Phone: strip invalid chars --- */
  fields.phone?.addEventListener('input', () => {
    fields.phone.value = fields.phone.value.replace(/[^0-9\+\-\s\(\)]/g, '');
  });

});
