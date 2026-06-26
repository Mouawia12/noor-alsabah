var i_uppy = new Uppy.Uppy({
    id: 'video',
    locale: Uppy.locales.ar_SA,
    debug: false,
    autoProceed: true,
    restrictions: {
        maxFileSize: i_max_file_size,
        maxTotalFileSize: i_max_file_size,
        allowedFileTypes: i_file_types,
        maxNumberOfFiles: i_max_number_of_files,
        minNumberOfFiles: 1,
    }
})
i_uppy.use(Uppy.Dashboard, {
    target: 'body',
    trigger: i_btn,
    height: 470,
    note: i_note,
	 hideRetryButton: true,
  hideCancelButton: true,
    plugins: ['Webcam'],
})
i_uppy.use(Uppy.XHRUpload, {
    headers: {
        'X-CSRF-TOKEN': csrf
    },
    id: 'video',
    endpoint: i_route,
    method: 'post',
    formData: true,
    fieldName: i_field_name,
    timeout: 0
})
i_uppy.setMeta({i_path: i_path});

i_uppy.on('complete', result => {
    console.log('successful files:', result.successful)
    console.log('failed files:', result.failed)
})
i_uppy.on('upload-success', (file, response) => {
    let result = response.body.uploadURL
    console.log(result);
    let option = document.createElement("option");
    option.text = result;
    option.value = result;
    option.setAttribute('selected', 'selected');
    i_input.add(option);
})


$('.uppy-Dashboard-poweredBy').hide().remove();
