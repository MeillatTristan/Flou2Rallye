const searchbar = document.getElementById('searchbar');

if (searchbar) {
  const input = searchbar.querySelector('input');
  input.addEventListener('focusin', (e) => {
    searchbar.classList.add('active');
  })

  input.addEventListener('focusout', (e) => {
    setTimeout(() => {
      searchbar.classList.remove('active');
    }, 200);
  })
}