// Datatable function
$(document).ready( function () {
    $('#fileTable').DataTable();
    
});

// Updating file
function updateFile(id) {
    $("#updateFileModal").modal("show");

    let updateFileID = $("#fileID-" + id).text();
    let updateFileTitle = $("#fileTitle-" + id).text();
    let updateFile = $("#file-" + id).text();
    let updateFileUploader = $("#fileUploader-" + id).text();

    $("#updateFileID").val(updateFileID);
    $("#updateFileTitle").val(updateFileTitle);
    $("#updateFile").text(updateFile);
    $("#updateFileUploader").val(updateFileUploader);
}

// Deleting file
function deleteFile(id) {
    if (confirm("Do you want to delete this file?")) {
        window.location = "./endpoint/delete-file.php?file=" + id;
    }
}

// Download the file
function downloadFile(fileID) {
    let downloadUrl = `./endpoint/download-file.php?fileID=${fileID}`;

    let downloadLink = document.createElement('a');
    downloadLink.href = downloadUrl;
    downloadLink.download = 'filename.ext';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

