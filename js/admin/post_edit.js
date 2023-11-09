$(document).ready(function () {

  $("#summernote").summernote({
    height: 300,
    url: '../uploads/posts_img/',
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ],
    callbacks: {
      onImageUpload: function (files, editor, welEditable) {

        for (var i = files.length - 1; i >= 0; i--) {
          sendFile(files[i], this);
        }
      }
    }
  });

  function sendFile(file, el) {
    var form_data = new FormData();
    form_data.append('file', file);
    form_data.append("action", "image");
    $.ajax({
      data: form_data,
      type: "POST",
      url: '../ajax',
      cache: false,
      contentType: false,
      processData: false,
      success: function (filename) {
        $(el).summernote('insertImage', url, filename);

        // $(el).summernote('editor.insertImage', '../uploads/posts_img/' + url);
      }
    });
  }


});

