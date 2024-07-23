   /*=============================================
    AGREGAR MULTIMEDIA CON DROPZONE
    =============================================*/
$(".multimediaFisica").dropzone({
    url: "../Tramites/plugins/dropzone/dropzone.js",
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg, image/png, application/pdf, video/mp4",
    maxFilesize: 20,
    maxFiles: 25,
    init: function () {
        this.on("addedfile", function (file) {
            arrayFiles.push({ id: id, value: file });
            arrayId.push(id);
        });
        this.on("removedfile", function (file) {
            var index = arrayFiles.indexOf(file);
            arrayFiles.splice(index, 1);
            arrayId.splice(index);
        });
    }
});
