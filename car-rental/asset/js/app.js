document.addEventListener('click', e => {
  if (e.target.matches('th[data-sort]')) {
    const table = e.target.closest('table');
    const tbody = table.querySelector('tbody');
    const idx   = Array.from(e.target.parentNode.children).indexOf(e.target);
    const rows  = Array.from(tbody.querySelectorAll('tr'));
    rows.sort((a,b)=> a.children[idx].textContent.localeCompare(b.children[idx].textContent));
    rows.forEach(r=>tbody.appendChild(r));
  }
});