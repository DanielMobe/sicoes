$( document ).ready(function() {
    console.log( "ready!" );

    $("#enviar").click(function() {
        //$("#kardex-pid-div").show();
        console.log('boton ->');
        return false;
    });


    $("#archivo").fileinput({
        uploadUrl: "http://localhost/file-upload.php",
        enableResumableUpload: true,
        resumableUploadOptions: {
           // uncomment below if you wish to test the file for previous partial uploaded chunks
           // to the server and resume uploads from that point afterwards
           // testUrl: "http://localhost/test-upload.php"http://localhost/test-upload.php"
        },
        uploadExtraData: {
            'uploadToken': 'SOME-TOKEN', // for access control / security 
        },
        maxFileCount: 5,
        allowedFileTypes: ['image'],    // allow only images
        showCancel: true,
        initialPreviewAsData: true,
        overwriteInitial: false,
        // initialPreview: [],          // if you have previously uploaded preview files
        // initialPreviewConfig: [],    // if you have previously uploaded preview files
        theme: 'fas',
        deleteUrl: "http://localhost/file-delete.php"
    }).on('fileuploaded', function(event, previewId, index, fileId) {
        console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
    }).on('fileuploaderror', function(event, data, msg) {
        console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
    }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
        console.log('File Batch Uploaded', preview, config, tags, extraData);
    });


});