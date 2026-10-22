function updateCount(input) {
    const counter = document.getElementById(input.dataset.counter);
    if (counter) counter.textContent = `${input.value.length.toLocaleString('id-ID')} / 10.000`;
}
document.querySelectorAll('[data-counter]').forEach((input) => {
    updateCount(input);
    input.addEventListener('input', () => updateCount(input));
});
document.addEventListener('submit', (event) => {
    const form = event.target;
    if (event.defaultPrevented || !form.matches('[data-loading]')) return;
    if (form.dataset.submitting === 'true') { event.preventDefault(); return; }
    const button = form.querySelector('button[type="submit"]');
    if (!button) return;
    form.dataset.submitting = 'true';
    button.dataset.originalHtml = button.innerHTML;
    button.disabled = true;
    button.setAttribute('aria-busy', 'true');
    button.textContent = form.dataset.loading;
});
window.addEventListener('pageshow', () => {
    document.querySelectorAll('[data-submitting]').forEach((form) => {
        delete form.dataset.submitting;
        const button = form.querySelector('button[data-original-html]');
        if (!button) return;
        button.innerHTML = button.dataset.originalHtml;
        button.disabled = false;
        button.removeAttribute('aria-busy');
    });
});
