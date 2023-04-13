$('#album_multipleFile').change((e) => {

  let files = e.target.files;
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
      $('#addIdentite').click();
      
    const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      $('#containerIdentite .collection-item .custom-file-input').last()[0].files = dataTransfer.files;

          var div = $('#containerIdentite .collection-item .previewImg').last()[0]
          console.log(div)
          div.innerHTML = "<img class='thumbnail' src='" + URL.createObjectURL(file) + "'" +
            "title='" + file.name + "'/>" + 
            "<span class='description'>" + file.name + "</span>";
        //Read the image
        $('#containerIdentite .collection-item').last()[0].classList.add('new')

  }
  
})