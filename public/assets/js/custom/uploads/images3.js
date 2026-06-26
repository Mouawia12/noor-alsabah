
var i_uppy3 = new Uppy.Uppy({
    id: 'video',
    locale: Uppy.locales.ar_SA,
    debug: false,
    autoProceed: true,
    restrictions: {
        maxFileSize: i_max_file_size3,
        maxTotalFileSize: i_max_file_size3,
        allowedFileTypes: i_file_types3,
        maxNumberOfFiles: i_max_number_of_files3,
        minNumberOfFiles: 1,
    }
})
i_uppy3.use(Uppy.Dashboard, {
    target: 'body',
    trigger: i_btn3,
    height: 470,
    note: i_note3,
})
i_uppy3.use(Uppy.XHRUpload, {
    headers: {
        'X-CSRF-TOKEN': csrf
    },
    id: 'video',
    endpoint: i_route3,
    method: 'post',
    formData: true,
    fieldName: i_field_name3,
    timeout: 0
})
i_uppy3.setMeta({i_path: i_path3});

i_uppy3.on('complete', result => {
    console.log('successful files:', result.successful)
    console.log('failed files:', result.failed)
})
i_uppy3.on('upload-success', (file, response) => {
    let result = response.body.uploadURL
    i_input3.value = result;

    // console.log(response.body);
    // console.log(response.body.uploadURL);
    // console.log(i_input.value);
})


$('.uppy-Dashboard-poweredBy').hide().remove();
