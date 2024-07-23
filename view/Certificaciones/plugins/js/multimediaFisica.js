   /*=============================================
                                                                                               AGREGAR MULTIMEDIA CON DROPZONE
                                                                                  =============================================*/
   // Configuración de Dropzone
   $(".multimediaFisica").dropzone({
       url: "../Tramites/plugins/dropzone/dropzone.js",
       addRemoveLinks: true,
       acceptedFiles: "image/jpeg, image/png, application/pdf",
       maxFilesize: 10,
       maxFiles: 1,
       dictDefaultMessage: "Arrastra archivos aquí para subirlos",
       dictFallbackMessage: "Tu navegador no soporta la subida de archivos mediante arrastrar y soltar.",
       dictFallbackText: "Por favor utiliza el formulario de respaldo que se encuentra más abajo para subir tus archivos como en los viejos tiempos.",
       dictFileTooBig: "El archivo es muy grande ({{filesize}}MiB). Tamaño máximo: {{maxFilesize}}MiB.",
       dictInvalidFileType: "No puedes subir archivos de este tipo.",
       dictResponseError: "El servidor respondió con el código {{statusCode}}.",
       dictCancelUpload: "Cancelar subida",
       dictUploadCanceled: "Subida cancelada.",
       dictCancelUploadConfirmation: "¿Estás seguro de que deseas cancelar esta subida?",
       dictRemoveFile: "Eliminar archivo",
       dictRemoveFileConfirmation: null,
       dictMaxFilesExceeded: "No puedes subir más archivos.",
       init: function() {



           this.on("addedfile", function(file) {
               // Verificar si ya existe un elemento con el mismo id en arrayFiles
               let existingIndex = arrayFiles.findIndex(item => item.id == id);


               if (existingIndex != -1) {

                   let dropzoneInstance = this; // Guardar la instancia de Dropzone en una variable local

                   // Si existe, eliminar el elemento existente de Dropzone
                   dropzoneInstance.removeFile(dropzoneInstance.files[0]); // Eliminar el archivo existente de Dropzone

                   // Si existe, eliminar el elemento existente
                   arrayFiles.splice(existingIndex, 1);
                   // También eliminar el id correspondiente de arrayId si existe
                   arrayId = arrayId.filter(item => item !== id);
                   console.log('Elemento existente eliminado del arrayFiles y arrayId:', id);
                   arrayFiles.push({ id: id, value: file });
                   arrayId.push(id);
               } else {
                   // Insertar el nuevo elemento
                   arrayFiles.push({ id: id, value: file });
                   arrayId.push(id);
               }

               console.log('Archivo añadido:', file, 'ID Documento:', id);
               console.log('Array de archivos:', arrayFiles);
           });

           this.on("removedfile", function(file) {

               //    Eliminar todos los elementos con el mismo id de arrayFiles y arrayId
               //    arrayFiles = arrayFiles.filter(item => item.id !== id);
               //    arrayId = arrayId.filter(item => item !== id);

               let tipoDocumentoId = $(file.previewElement).closest('.multimediaFisica').data('tipo-documento-id');
               arrayFiles = arrayFiles.filter(item => item.value !== file);
               arrayId = arrayId.filter(id => id !== tipoDocumentoId);

               console.log('Elementos eliminados del arrayFiles y arrayId con ID:', id);
               console.log('Array de archivos:', arrayFiles);

           });

           //    this.on("maxfilesexceeded", function(file) {
           //        this.removeFile(file); // Eliminar automáticamente el archivo adicional

           //        Swal.fire({
           //            title: "Error",
           //            text: "No puedes subir más de un archivo.",
           //            icon: "error",
           //            showCancelButton: true,
           //            confirmButtonColor: "#3d85c6",
           //            confirmButtonText: "OK"
           //        });
           //    });
       }
   });


   //    // Configuración Dropzone
   //    $(".multimediaFisica").each(function() {
   //        $(this).dropzone({
   //            url: "../Tramites/plugins/dropzone/dropzone.js",
   //            addRemoveLinks: true,
   //            acceptedFiles: "image/jpeg, image/png, application/pdf",
   //            maxFilesize: 10,
   //            maxFiles: 1,
   //            init: function() {
   //                this.on("addedfile", function(file) {
   //                    let tipoDocumentoId = $(file.previewElement).closest('.multimediaFisica').data('tipo-documento-id');
   //                    arrayFiles.push({ id: tipoDocumentoId, value: file });
   //                    arrayId.push(tipoDocumentoId);
   //                    console.log('Archivo añadido:', file, 'ID Documento:', tipoDocumentoId);
   //                    console.log('Array de archivos:', arrayFiles);
   //                });
   //                this.on("removedfile", function(file) {
   //                    let tipoDocumentoId = $(file.previewElement).closest('.multimediaFisica').data('tipo-documento-id');
   //                    arrayFiles = arrayFiles.filter(item => item.value !== file);
   //                    arrayId = arrayId.filter(id => id !== tipoDocumentoId);
   //                    console.log('Archivo eliminado:', file, 'ID Documento:', tipoDocumentoId);
   //                    console.log('Array de archivos actualizado:', arrayFiles);
   //                });
   //            }
   //        });
   //    });