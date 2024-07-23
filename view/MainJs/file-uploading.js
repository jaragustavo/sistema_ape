// Global array to store selected files
var selectedFiles = [];

var choosePhotoBtn = document.getElementById("data_nuevo_post");

if (choosePhotoBtn) {
    document.getElementById('choosePhotoBtn').addEventListener('click', function () {
        document.getElementById('photoInput').click();
    });
    
    document.getElementById('photoInput').addEventListener('change', function () {
        var files = this.files;
    
        // Loop through selected files and add them to the global array
        for (var i = 0; i < files.length; i++) {
            selectedFiles.push(files[i]);
        }
    
        // Clear existing previews
        var previewContainer = document.getElementById('filePreviewContainer');
        previewContainer.innerHTML = '';
    
        // Display the files in rows of three elements
        for (var i = 0; i < selectedFiles.length; i++) {
            var file = selectedFiles[i];
    
            // Creating a row to display the files in rows of three elements
            if (i % 3 === 0) {
                var divRow = document.createElement('div');
                divRow.className = 'row';
                previewContainer.appendChild(divRow);
            }
    
            // Create a div for file preview
            var filePreviewDiv = document.createElement('div');
            filePreviewDiv.className = 'file-preview-item col-md-4';
    
            // Create an element for different file types
            var fileElement = createFileElement(file);
    
            // Append the file element to the preview div
            filePreviewDiv.appendChild(fileElement);
    
            // Append the file preview to the container
            previewContainer.lastChild.appendChild(filePreviewDiv);
        }
    });
}


// Function to create appropriate elements for different file types
function createFileElement(file) {
    var fileElement;

    if (file.type.startsWith('image/')) {
        fileElement = createImageElement(file);
    } else if (file.type === 'application/pdf') {
        fileElement = createPdfElement(file);
    } else if (file.type.startsWith('video/')) {
        fileElement = createVideoElement(file);
    } else {
        // For other file types, display a generic element
        fileElement = createGenericElement(file);
    }

    return fileElement;
}

// Function to create an element for image files
function createImageElement(file) {
    var thumbnailImg = document.createElement('img');
    thumbnailImg.setAttribute('data-dz-thumbnail', '');
    thumbnailImg.setAttribute('alt', file.name);

    var reader = new FileReader();
    reader.onload = function (e) {
        thumbnailImg.setAttribute('src', e.target.result);
    };
    reader.readAsDataURL(file);

    // Set max-width and max-height for the thumbnail
    thumbnailImg.style.maxWidth = '150px';
    thumbnailImg.style.maxHeight = '150px';

    return thumbnailImg;
}

// Function to create an element for PDF files
function createPdfElement(file) {
    var embedElement = document.createElement('embed');
    embedElement.src = URL.createObjectURL(file);
    embedElement.type = 'application/pdf';
    embedElement.style.width = '150px';
    embedElement.style.height = '150px';

    return embedElement;
}

// Function to create an element for video files
function createVideoElement(file) {
    var embedElement = document.createElement('embed');
    embedElement.src = URL.createObjectURL(file);
    embedElement.type = file.type;
    embedElement.style.width = '150px';
    embedElement.style.height = '150px';

    return embedElement;
}

// Function to create a generic element for other file types
function createGenericElement(file) {
    var genericElement = document.createElement('p');
    genericElement.textContent = 'Unsupported file type: ' + file.name;

    return genericElement;
}