<head>
  <meta charset="UTF-8">
  <title>Subir Archivos a buckets</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</head>

<style>
  * { 
    margin: 0;
    padding: 0;
    color:#fff;
  }

  body {
    background: #616161;
  }

  .accordion {
    background: #373737;
  }

  .accordion-button {
    color:#fff;
  }

  #images-gallery img {
    width: 300px;
    height: 300px;
    margin-top: 10px;
  }
</style>

<body>
  <nav class="navbar navbar-dark bg-dark">
    <h1>Bucket File Upload</h1>
  </nav>
  <form id="fileUploadForm" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id ="fileUpload"/><hr>
    <input type="submit" name="upload" value="Upload" class="btn btn-dark"/>    
    <span id="uploadingmsg"></span>
    <hr />
  </form>
  <div class="accordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="flush-headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
        Response (JSON)
        </button>
      </h2>
      <div id="flush-collapseOne" class="accordion-collapse collapse">
        <div class="accordion-body"><pre id="json">Respuesta Json</pre></div>
      </div>
    </div>
  </div>
  <hr>
  <div class="container">
    <div class="row" id="images-gallery">
    </div>
  </div>
</body>
</html>

<script>
  const $imageGallery = $("#images-gallery");
  const $formUpload = $("#fileUploadForm");
  const $uploadingMessage = $("#uploadingmsg");
  const $fileUpload =  $("#fileUpload");
  const $responseMessage = $("#flush-collapseOne");
  const $json = $("#json");

  const update_images = () => {    
    let url = "list-files.php";

    $.ajax({
      type: 'POST',
      url: url,
      contentType: false,
      processData: false,
    }).done((response) =>{
      $imageGallery.empty();
      response.forEach(a=>{
        $imageGallery.append(
          `<div class="col-4">
            <a href='${a.link}'>
              <img src='${a.link}' alt='${a.name}' />
            </a>
           </div>`
        );
      })
    }).fail((data) =>  {
      alert("loading error");
    });
  };

  $formUpload.submit((e) =>{
    e.preventDefault();
    let action = "requests.php?action=upload";

    $uploadingMessage.html("Uploading...");
    let data = new FormData(e.target);
    $.ajax({
      type: 'POST',
      url: action,
      data: data,
      contentType: false,
      processData: false,
    }).done((response) =>{
      $uploadingMessage.html("");
      $json.html(JSON.stringify(response, null, 4));
      $fileUpload.val('');
      $responseMessage.collapse('show');
      update_images();
    }).fail((data) =>  {
      alert("upload error");
    });
  });

  $fileUpload.click(()=>{
    $responseMessage.collapse('hide');
  });

  update_images();
  </script>