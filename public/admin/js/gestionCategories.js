const inputEnter = document.getElementById('addCategories');
const inputHidden = document.getElementById('album_categories');
const containerCategories = document.getElementById('containerCategories');
let activeCategories = [];

if (inputEnter.value != "") {
  console.log(inputEnter.value.split(" "))
  activeCategories = inputEnter.value.split(" ")
  inputEnter.value = "";
  displayCategories(activeCategories);
}

if (inputEnter) {
  inputEnter.addEventListener('keyup', (e) => {
    const touchName = e.code;
    if(touchName === "Space") {
      const valueInputEnter = inputEnter.value.replace(/\s/g, '');
      addCategories(valueInputEnter);
      inputEnter.value = "";
    }
  })
}

function addCategories(categoryName) {
  if (!(activeCategories.indexOf(categoryName) >= 0)) {
    activeCategories.push(categoryName);
    inputHidden.value = activeCategories.join(" ");
    displayCategories(activeCategories);
  }
}

function removeCategories(categoryName) {
  if (activeCategories.indexOf(categoryName) >= 0) {
    activeCategories.splice(activeCategories.indexOf(categoryName), 1);
    inputHidden.value = activeCategories.join(" ");
    displayCategories(activeCategories);
  }
}

function displayCategories(categories) {
  containerCategories.innerHTML = "";
  categories.forEach(category => {
    const span = document.createElement('span');
    span.classList.add("category");
    span.dataset.id = category;

    const close = document.createElement('div');
    close.classList.add('close');
    
    const iconDelete = document.createElement('i');
    iconDelete.classList.add('fa-solid');
    iconDelete.classList.add('fa-xmark');

    close.appendChild(iconDelete);

    close.addEventListener('click', (e) => {
      const valueToRemove = e.target.parentElement.parentElement.dataset.id;
      removeCategories(valueToRemove);
    })

    span.textContent = category;
    span.appendChild(close);

    
    containerCategories.appendChild(span);
  });
}