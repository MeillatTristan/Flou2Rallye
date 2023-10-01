

$('#album_multipleFile').change(async (e) => {

  let files = e.target.files;
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    $('#addIdentite').click();

    let exifData = await exifr.parse(file, true);
    let keywords = exifData.Keywords;
    if (Array.isArray(keywords)) {
      for (let j = 0; j < keywords.length; j++) {
        const keyword = decodeURIComponent(escape(keywords[j]));
        addCategories(keyword);
      }
    } else if(keywords) {
      addCategories(keywords.toString("utf8"));
    }
      
    const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      $('#containerIdentite .collection-item .custom-file-input').last()[0].files = dataTransfer.files;

          var div = $('#containerIdentite .collection-item .previewImg').last()[0]
          div.innerHTML = "<img class='thumbnail' src='" + URL.createObjectURL(file) + "'" +
            "title='" + file.name + "'/>" + 
            "<span class='description'>" + file.name + "</span>";
        //Read the image
        $('#containerIdentite .collection-item').last()[0].classList.add('new')

  }
  
})