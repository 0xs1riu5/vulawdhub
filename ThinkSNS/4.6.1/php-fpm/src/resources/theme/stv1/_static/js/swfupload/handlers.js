function preLoad() {
    if (!this.support.loading) {
        ui.error("您的浏览器所支持的flash低于9.0，请下载安装flash扩展");
        return false;
    }
}
function loadFailed() {
    ui.error("加载上传组件时失败，请刷新后稍后再试");
}


function fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        this.startUpload();
    } catch (ex) {

    }
}



function fileDialogStart() {
    this.cancelUpload();
}



function fileQueueError(file, errorCode, message)  {
    try {
        // Handle this error separately because we don't want to create a FileProgress element for it.
        switch (errorCode) {
        case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
            ui.error("上传了过多文件，" + (message === 0 ? "上传文件限制" : "你可以选择 " + (message > 1 ? "上传" + message + " 个文件." : "一个文件.")));
            return;
        case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
            ui.error("上传的文件请小于"+this.settings.file_size_limit);
            this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            return;
        case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            ui.error("待上传的文件是空文件，请重新选一个");
            this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            return;
        case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
            ui.error("待上传的文件请选择"+this.settings.file_types+"类型文件");
            this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            return;
        default:
            ui.error("上传过程中发生异常，请刷新后稍后再上传");
            this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            return;
        }
    } catch (e) {
    }
}

function fileQueued(file) {
    try {
        var txtFileName = document.getElementById(this.customSettings.success_target);
        txtFileName.value = file.name;
    } catch (e) {
    }

}

function uploadProgress(file, bytesLoaded, bytesTotal) {

    try {
        $('#'+this.customSettings.success_target).hide();
       this.setButtonDisabled(true);
    } catch (e) {
    }
}

function uploadSuccess(file, serverData) {
    try {
        if (serverData === " ") {
            this.customSettings.upload_successful = false;
            ui.error('上传过程中出现错误，请重新上传');
        } else {
            var res = eval('('+serverData+')');
            if(res.status){
                this.customSettings.upload_successful = true;
                document.getElementById(this.customSettings.hidden_target).value = res.info[0]['attach_id'];
                $('#'+this.customSettings.success_target).show();
            }else{
                this.customSettings.upload_successful = false;
                ui.error(res.info);
            }
            
        }
    } catch (e) {
    }
}

function uploadComplete(file) {
    try {
        if (false == this.customSettings.upload_successful) {
            $('#'+this.customSettings.success_target).hide();
             document.getElementById(this.customSettings.hidden_target).value = "";
        } 
        this.setButtonDisabled(false);
    } catch (e) {
        $('#'+this.customSettings.success_target).hide();
         ui.error('上传过程中出现错误，请重新上传');
    }
}

function uploadError(file, errorCode, message) {
    try {
        if (errorCode === SWFUpload.UPLOAD_ERROR.FILE_CANCELLED) {
            // Don't show cancelled error boxes
            return;
        }
        
        $('#'+this.customSettings.success_target).hide();
          document.getElementById(this.customSettings.hidden_target).value = "";
        
        // Handle this error separately because we don't want to create a FileProgress element for it.
        switch (errorCode) {
        case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
            ui.error("上传错误，错误的上传地址");
            this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
            return;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
            ui.error("只允许上传一个文件");
            this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            return;
        case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
        case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
            break;
        default:
            ui.error("上传意外失败，刷新稍后再试");
            this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            return;
        }

        switch (errorCode) {
        case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
            progress.setStatus("Upload Error");
            this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
            progress.setStatus("Upload Failed.");
            this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.IO_ERROR:
            progress.setStatus("Server (IO) Error");
            this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
            progress.setStatus("Security Error");
            this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
            progress.setStatus("Upload Cancelled");
            this.debug("Error Code: Upload Cancelled, File name: " + file.name + ", Message: " + message);
            break;
        case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
            progress.setStatus("Upload Stopped");
            this.debug("Error Code: Upload Stopped, File name: " + file.name + ", Message: " + message);
            break;
        }
    } catch (ex) {
    }
}
