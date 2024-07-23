   /*=============================================
    AGREGAR MULTIMEDIA CON DROPZONE
    =============================================*/
$(".multimediaFisica").dropzone({
    url: "../Tramites/plugins/dropzone/dropzone.js",
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg, image/png",
    maxFilesize: 10,
    maxFiles: 1,
    init: function () {
        this.on("addedfile", function (file) {
            arrayFiles.push(file); 
        });
        this.on("removedfile", function (file) {
            var index = arrayFiles.indexOf(file);
            arrayFiles.splice(index);
        });
    }
});
