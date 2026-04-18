const EYE_OPEN = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
const EYE_CLOSED = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;

document.querySelectorAll('.toggle-password').forEach(btn => btn.innerHTML = EYE_OPEN);

function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const isHidden = field.type === 'password';
    field.type = isHidden ? 'text' : 'password';
    btn.innerHTML = isHidden ? EYE_CLOSED : EYE_OPEN;
}

function checkStrength(value) {
    const bar = document.getElementById('strength-bar');
    const score = [value.length >= 8, /\d/.test(value), /[^a-zA-Z0-9]/.test(value), /[A-Z]/.test(value)].filter(Boolean).length;
    bar.className = 'strength-bar';
    if (value.length === 0) return;
    if (score <= 1) bar.classList.add('weak');
    else if (score <= 3) bar.classList.add('medium');
    else bar.classList.add('strong');
}

function checkMatch() {
    const pw   = document.getElementById('password').value;
    const cpw  = document.getElementById('confirm_password').value;
    const hint = document.getElementById('match-hint');
    if (cpw.length === 0) { hint.textContent = ''; return; }
    hint.textContent = pw === cpw ? '✓ Passwords match.' : '✗ Passwords do not match.';
    hint.style.color = pw === cpw ? 'green' : 'red';
}