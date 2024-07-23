   /*=============================================
    AGREGAR MULTIMEDIA CON DROPZONE
    =============================================*/
$(".multimediaFisica").dropzone({
    url: "../Tramites/plugins/dropzone/dropzone.js",
    addRemoveLinks: true,
    acceptedFiles: "video/mp4",
    maxFilesize: 50,
    maxFiles: 1,
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
