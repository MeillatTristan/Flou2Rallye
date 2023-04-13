
if (document.getElementById('paginationContainer')) {

  duplicateChildNodes('albumsContainer')
  duplicateChildNodes('albumsContainer')
  duplicateChildNodes('albumsContainer')
  duplicateChildNodes('albumsContainer')
  duplicateChildNodes('albumsContainer')
  duplicateChildNodes('albumsContainer')
  duplicateChildNodes('albumsContainer')
  var sendSearch = document.getElementById('send_search');
  var selectNumberCardsDisplay = document.getElementById('filterNumber');
  var selectFilter = document.getElementById('filter');
  var inputSearch = document.getElementById('search_search');
  var albumsContainer = document.getElementById("albumsContainer");
  var albumsNoSearch = [].slice.call(albumsContainer.children);
  var searchValueOtherPage = document.getElementById('searchValue').dataset.value;
  
  var searchInProgress = false;


  sendSearch.addEventListener('submit', (e) => {
    e.preventDefault();
  })

  var numberCardsDisplay = parseInt(selectNumberCardsDisplay.value);
  createButton(numberCardsDisplay);

  selectNumberCardsDisplay.addEventListener('change', (e) => {
    numberCardsDisplay = parseInt(selectNumberCardsDisplay.value);
    createButton(numberCardsDisplay);
  })

  selectFilter.addEventListener('change', (e) => {
    let filter = selectFilter.value;
    orderCards(filter);
    createButton(numberCardsDisplay);
  })

  
  if (searchValueOtherPage != "null") {
    search(searchValueOtherPage)
  }

  inputSearch.addEventListener('keyup', (e) => {
    const searchValue = inputSearch.value.toLowerCase().replace(/\./g, '');
    search(searchValue);
  })

  numberCards();


}

function orderCards(filter) {
  const parentNode = document.getElementById("albumsContainer");
  const e = parentNode.children;
  let sortArray = [].slice.call(e);
  sortArray.sort(function (a, b) {
    
    let aFilter = parseFloat(a.dataset[filter]);
    let bFilter = parseFloat(b.dataset[filter]);
    return bFilter - aFilter
  })

  sortArray.forEach(function (val, index) {
    parentNode.appendChild(val);
  });


}

function search(searchValue) {
    let albums = albumsNoSearch;
    if (searchValue.length >= 3) {
      searchInProgress = searchValue;
      for (let i = 0; i < albums.length; i++) {
        const album = albums[i];
        const albumCategories = album.dataset.categories.split(" ").filter(n => n);
        const albumTitle = album.dataset.title.toLowerCase().replace(/\./g, '');
        let searchInCategory = false;

        album.classList.remove('result');
        album.classList.remove('noResult');
        for (let j = 0; j < albumCategories.length; j++) {
          const category = albumCategories[j].toLowerCase().replace(/\./g, '');
          if (category.includes(searchValue)) {
            searchInCategory = true;
          }
        }

        if (searchInCategory || albumTitle.includes(searchValue)) {
          album.classList.remove('hide')
          albumsContainer.appendChild(album)

        } else if (album.parentElement === albumsContainer) {
          albumsContainer.removeChild(album)
        }
      }
    } else {
      searchInProgress = false;
      for (let i = 0; i < albums.length; i++) {
        const album = albums[i];
        albumsContainer.appendChild(album)
      }
    }
    numberCards();
    createButton(numberCardsDisplay);
}

function numberCards() {
  let cardsResult = document.querySelectorAll('.cardAlbum');
  const numberCards = cardsResult.length;
  let numberAlbums = document.getElementById('numberAlbums');
  if (searchInProgress === false) {
    numberAlbums.innerHTML = "<p> Il y a <span class='number'>" + numberCards + "</span> albums </p>";
  } else {
    numberAlbums.innerHTML = "<p> Il y a <span class='number'>" + numberCards + "</span> albums avec la recherche '" + searchInProgress + "' </p>";
  }
}

function createButton(numberCardsDisplay) {
  let numbersButtons = 0;
  const paginationButtons = document.getElementById('paginationContainer');
  paginationButtons.innerHTML = "";
  const cardsResult = document.getElementsByClassName('cardAlbum');
  const numberCards = cardsResult.length;

  if (numberCards > numberCardsDisplay) {
    for (let index = 0; index < numberCards; index += numberCardsDisplay) {
      numbersButtons++;
    }

    for (let index = 0; index < numbersButtons; index++) {
      let button = document.createElement('button');
      button.classList.add('paginationButton');
      button.textContent = index + 1;
      button.dataset.page = index + 1;
      if (index === 0) {
        button.classList.add('active');
        pagination(button.dataset.page, cardsResult, numberCardsDisplay)
      }
      paginationButtons.appendChild(button);
      button.addEventListener('click', (e) => {
        pagination(button.dataset.page, cardsResult, numberCardsDisplay)
        for (let j = 0; j < paginationButtons.children.length; j++) {
          const buttonPlace = paginationButtons.children[j];
          buttonPlace.classList.remove('active');
        }
        button.classList.add('active')
        $([document.documentElement, document.body]).animate({
          scrollTop: $("#albumsContainer").offset().top - 200
        }, 1000);
      })
    }
  }
}

function pagination(page, container, numberCards) {
  const startDisplay = page * numberCards + 1 - numberCards;
  const endDisplay = startDisplay + numberCards - 1;

  for (let index = 1; index < container.length + 1; index++) {
    const card = container[index - 1];
    if (index < startDisplay || index > endDisplay) {
      card.classList.add('hide');
    } else {
      card.classList.remove('hide');
    }
  }
}

// for testing
function duplicateChildNodes(parentId) {
  var parent = document.getElementsByClassName(parentId)[0];
  NodeList.prototype.forEach = Array.prototype.forEach;
  var children = parent.childNodes;
  children.forEach(function (item) {
    var cln = item.cloneNode(true);
    parent.appendChild(cln);
  });
};