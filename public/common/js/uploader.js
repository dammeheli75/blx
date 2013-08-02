// Handler
function loadedHandler() {

}

// Uploader
var uploaderGlobalSettings = {
    flash_url: "swfupload/flash/uploader.swf",
    file_types: "*.jpg;*.png",
    file_size_limit: "2MB",
    file_upload_limit: 0,
    file_queue_limit: 0,
    prevent_swf_caching: false,
    button_cursor: SWFUpload.CURSOR.HAND,
    button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
    button_disabled: false,

//    Global Handler
    swfupload_loaded_handler: loadedHandler
};

// Handler
function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        /* I want auto start the upload and I can do that here */
        this.startUpload();
    } catch (ex) {
        this.debug(ex);
    }
}

function fileQueuedHandler(file) {

}

function fileQueueError(file, errorCode, message) {
    // Identity class
    var targetClass = '';
    var maxHeight = 0;

    if (this.customSettings.type == 'idCardFront') {
        targetClass = '.id-card-front';
        maxHeight = 82;
    }
    else if (this.customSettings.type == 'idCardBack') {
        targetClass = '.id-card-back';
        maxHeight = 82;
    } else {
        targetClass = '.portrait-photo-holder';
        maxHeight = 120;
    }

    try {
        var errorModal = $('#online-filing-error-modal');

        if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
            errorModal.find('ul').html('<li>Số file upload quá giới hạn cho phép, bạn chỉ được chọn 1 file mỗi lần.</li>').modal('show');
            return;
        }

        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                console.log("FILE_EXCEEDS_SIZE_LIMIT");
                errorModal.find('ul').html('<li>File đã chọn vượt quá dung lượng cho phép, file của bạn phải < 2MB</li>').modal('show');
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                errorModal.find('ul').html('<li>File đã chọn có kích thước 0 byte (Lỗi). Hãy chọn file khác.</li>').modal('show');
                break;
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                errorModal.find('ul').html('<li>Định dạng file không hợp lệ. Bạn chỉ được upload các file .jpg và .png</li>').modal('show');
                break;
            default:
                if (file !== null) {
                    errorModal.find('ul').html('<li>Lỗi không xác đinh, hãy thử lại.</li>').modal('show');
                }
                break;
        }

        $('#online-filing').find(targetClass + ' .progress-holder').css('height', "0");
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadStartHandler() {

}

function uploadProgressHandler(file, bytesLoaded, bytesTotal) {
    // Identity class
    var targetClass = '';
    var maxHeight = 0;

    if (this.customSettings.type == 'idCardFront') {
        targetClass = '.id-card-front';
        maxHeight = 82;
    }
    else if (this.customSettings.type == 'idCardBack') {
        targetClass = '.id-card-back';
        maxHeight = 82;
    } else {
        targetClass = '.portrait-photo-holder';
        maxHeight = 120;
    }

    var complete = bytesLoaded / bytesTotal;
    $('#online-filing').find(targetClass + ' .progress-holder').css('height', complete * maxHeight);
    $('#online-filing').find(targetClass + ' .progress-holder span').html(Math.round(complete * 100) + ' &#37;');
}

function uploadErrorHandler(file, errorCode, message) {
    // Identity class
    var targetClass = '';

    if (this.customSettings.type == 'idCardFront') {
        targetClass = '.id-card-front';
    } else if (this.customSettings.type == 'idCardBack') {
        targetClass = '.id-card-back';
    } else {
        targetClass = '.portrait-photo-holder';
    }//

    try {
        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                errorModal.find('ul').html('<li>Không thể upload ảnh do: Lỗi kết nối</li>').modal('show');
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                errorModal.find('ul').html('<li>Không thể upload ảnh.</li>').modal('show');
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                errorModal.find('ul').html('<li>Số file upload quá giới hạn cho phép.</li>').modal('show');
                break;
            default:
                errorModal.find('ul').html('<li>Lỗi trong quá trình upload ảnh, hãy thử lại</li>').modal('show');
                break;
        }

        $('#online-filing').find(targetClass + ' .progress-holder').css('height', "0");
    } catch (ex) {
        this.debug(ex);
    }
}

function uploadSuccessHandler(file, serverResponse, status) {
    // Identity class
    var targetClass = '';
    var maxHeight = 0;
    var inputHiddenClass = '';

    if (this.customSettings.type == 'idCardFront') {
        targetClass = '.id-card-front';
        maxHeight = 82;
        inputHiddenClass = 'id-card-front-url';
    }
    else if (this.customSettings.type == 'idCardBack') {
        targetClass = '.id-card-back';
        maxHeight = 82;
        inputHiddenClass = 'id-card-back-url';
    } else {
        targetClass = '.portrait-photo-holder';
        maxHeight = 120;
        inputHiddenClass = 'portrait-photo-url';
    }

    if (status) {
        if (serverResponse) {
            serverResponse = jQuery.parseJSON(serverResponse);

            console.log(serverResponse);

            //noinspection JSUnresolvedVariable
            $('input[name="' + inputHiddenClass + '"]').val(serverResponse.image_url);
            //noinspection JSUnresolvedVariable
            $('#online-filing-form').find(targetClass).css('background', 'url("http://localhost/BLX.VN/upload-content/' + decodeURIComponent(serverResponse.image_url) + '")');
            $('#online-filing').find(targetClass + ' .progress-holder').css('height', "0");
        }
    }
}

function uploadCompleteHandler(file, serverResponse) {

}


// Portrait Photo

var portraitPhotoUploaderSettings = jQuery.extend(uploaderGlobalSettings, {
//    Upload URL
    upload_url: "http://localhost/BLX.VN/upload.php?type=portrait-photo",
//    Button
    button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
    button_placeholder_id: "portrait-photo-uploader",
    button_width: "90",
    button_height: "120",
    file_dialog_complete_handler: fileDialogComplete,
    file_queued_handler: fileQueuedHandler,
    file_queue_error: fileQueueError,
    upload_start_handler: uploadStartHandler,
    upload_progress_handler: uploadProgressHandler,
    upload_error_handler: uploadErrorHandler,
    upload_success_handler: uploadSuccessHandler,
    upload_complete_handler: uploadCompleteHandler,
    custom_settings: {
        type: 'portraitPhoto'
    }
});

var portraitPhotoUploader = new SWFUpload(portraitPhotoUploaderSettings);


// ID Card Front Uploader

var idCardFrontPhotoUploaderSettings = jQuery.extend(uploaderGlobalSettings, {
//    Upload URL
    upload_url: "http://localhost/BLX.VN/upload.php?type=id-card-front",
//    Button
    button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
    button_placeholder_id: "id-card-front-uploader",
    button_width: "120",
    button_height: "82",
    file_dialog_complete_handler: fileDialogComplete,
    file_queued_handler: fileQueuedHandler,
    file_queue_error: fileQueueError,
    upload_start_handler: uploadStartHandler,
    upload_progress_handler: uploadProgressHandler,
    upload_error_handler: uploadErrorHandler,
    upload_success_handler: uploadSuccessHandler,
    upload_complete_handler: uploadCompleteHandler,
    custom_settings: {
        type: 'idCardFront'
    }
});

var idCardFrontPhotoUploader = new SWFUpload(idCardFrontPhotoUploaderSettings);

// ID Card Front Uploader

var idCardBackPhotoUploaderSettings = jQuery.extend(uploaderGlobalSettings, {
//    Upload URL
    upload_url: "http://localhost/BLX.VN/upload.php?type=id-card-back",
//    Button
    button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
    button_placeholder_id: "id-card-back-uploader",
    button_width: "120",
    button_height: "82",
    file_dialog_complete_handler: fileDialogComplete,
    file_queued_handler: fileQueuedHandler,
    file_queue_error: fileQueueError,
    upload_start_handler: uploadStartHandler,
    upload_progress_handler: uploadProgressHandler,
    upload_error_handler: uploadErrorHandler,
    upload_success_handler: uploadSuccessHandler,
    upload_complete_handler: uploadCompleteHandler,
    custom_settings: {
        type: 'idCardBack'
    }
});

var idCardBackPhotoUploader = new SWFUpload(idCardBackPhotoUploaderSettings);