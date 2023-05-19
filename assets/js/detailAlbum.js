const photosData = document.getElementsByClassName('dataPhoto');
const closeLightbox = document.getElementById('closeLigthbox');
const thumbnailPhotos = document.getElementsByClassName('cardAlbum');
const photoPlaceholder = document.getElementById('imgPlacehold');
const carouselPhoto = document.getElementById('carouselPhoto');
const navLeft = document.getElementById('navLeft');
const navRight = document.getElementById('navRight');
let activeId = photoPlaceholder.dataset.id;

for (let i = 0; i < thumbnailPhotos.length; i++) {
  const thumbPhoto = thumbnailPhotos[i];
  const idPhoto = thumbPhoto.dataset.id;
  thumbPhoto.addEventListener('click', (e) => { // on ajoute un listener sur chaque thumbnail qui ouvre la lightbox et change la src de l'image
    carouselPhoto.classList.add('active');
    const photoData = document.getElementById('data'+idPhoto)
    photoPlaceholder.src = photoData.src;
    activeId = idPhoto
  })
}

navLeft.addEventListener('click', (e) => {
  if (activeId == 1) {
    const photoData = document.getElementById('data'+photosData.length)
    photoPlaceholder.src = photoData.src;
    activeId = photosData.length;
  } else {
    const prevId = parseInt(activeId) - 1;
    const photoData = document.getElementById('data'+ prevId)
    photoPlaceholder.src = photoData.src;
    activeId = prevId;
  }
})

navRight.addEventListener('click', (e) => {
  if (activeId == photosData.length) {
    const photoData = document.getElementById('data1')
    photoPlaceholder.src = photoData.src;
    activeId = 1;
  } else {
    const nextId = parseInt(activeId) + 1;
    const photoData = document.getElementById('data'+ nextId)
    photoPlaceholder.src = photoData.src;
    activeId = nextId;
  }
})

closeLightbox.addEventListener('click', (e) => {
  carouselPhoto.classList.remove('active');
})

const buttonComment = document.getElementById('openComment');
const closeComment = document.getElementById('closeComment');
const layerComment = document.getElementById('layerComment');
const containerComment = document.getElementById('containerComments');
buttonComment.addEventListener('click', (e) => {
  containerComment.classList.add('active');
  document.body.style.overflow = 'hidden';
})

closeComment.addEventListener('click', (e) => {
  containerComment.classList.remove('active');
  document.body.style.overflow = 'initial';
})

layerComment.addEventListener('click', (e) => {
  containerComment.classList.remove('active');
  document.body.style.overflow = 'initial';
})