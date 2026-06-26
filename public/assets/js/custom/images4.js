
var i_uppy4 = new Uppy.Uppy({
    id: 'video',
    locale: Uppy.locales.ar_SA,
    debug: false,
    autoProceed: true,
    restrictions: {
        maxFileSize: i_max_file_size2,
        maxTotalFileSize: i_max_file_size2,
        allowedFileTypes: i_file_types2,
        maxNumberOfFiles: i_max_number_of_files2,
        minNumberOfFiles: 1,
    }
})
i_uppy4.use(Uppy.Dashboard, {
    target: 'body',
    trigger: i_btn4,
    height: 470,
    note: i_note2,
})
i_uppy4.use(Uppy.XHRUpload, {
    headers: {
        'X-CSRF-TOKEN': csrf
    },
    id: 'video',
    endpoint: i_route2,
    method: 'post',
    formData: true,
    fieldName: i_field_name4,
    timeout: 0
})
i_uppy4.setMeta({i_path: i_path2});

i_uppy4.on('complete', result => {
    console.log('successful files:', result.successful)
    console.log('failed files:', result.failed)
})
i_uppy4.on('upload-success', (file, response) => {
    let result = response.body.uploadURL
    i_input4.value = result;

    // console.log(response.body);
    // console.log(response.body.uploadURL);
    // console.log(i_input.value);
})


$('.uppy-Dashboard-poweredBy').hide().remove();
