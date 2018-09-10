/* Demo Note:  This demo uses a FileProgress class that handles the UI for displaying the file name and percent complete.
The FileProgress class is not part of SWFUpload.
*/


/* **********************
Event Handlers
These are my custom event handlers to make my
web application behave the way I went when SWFUpload
completes different tasks.  These aren't part of the SWFUpload
package.  They are part of my application.  Without these none
of the actions SWFUpload makes will show up in my application.
********************** */

function swfUploadPreLoad() {
	var self = this;
	var loading = function () {
		document.getElementById("divSWFUploadUI").style.display = "none";
		document.getElementById("divLoadingContent").style.display = "";

		var longLoad = function () {
			document.getElementById("divLoadingContent").style.display = "none";
			document.getElementById("divLongLoading").style.display = "";
		};
		this.customSettings.loadingTimeout = setTimeout(function () {
			longLoad.call(self)
		},
		15 * 1000
		);
	};

	this.customSettings.loadingTimeout = setTimeout(function () {
		loading.call(self);
	},
	1*1000
	);
}
function swfUploadLoaded() {
	var self = this;
	clearTimeout(this.customSettings.loadingTimeout);
	document.getElementById("divSWFUploadUI").style.visibility = "visible";
	document.getElementById("divSWFUploadUI").style.display = "block";
	document.getElementById("divLoadingContent").style.display = "none";
	document.getElementById("divLongLoading").style.display = "none";
	document.getElementById("divAlternateContent").style.display = "none";
	document.getElementById("btnUpload").onclick = function () { self.startUpload(); };
}

function swfUploadLoadFailed() {
	clearTimeout(this.customSettings.loadingTimeout);
	document.getElementById("divSWFUploadUI").style.display = "none";
	document.getElementById("divLoadingContent").style.display = "none";
	document.getElementById("divLongLoading").style.display = "none";
	document.getElementById("divAlternateContent").style.display = "";
}


function fileQueued(file) {
	try {
		FileProgress(file, this.customSettings.myFileListTarget,this);
	} catch (ex) {
		this.debug(ex);
	}
}

function fileQueueError(file, errorCode, message) {
	try {

		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert(message == 0 ? "已经达到一次性上传的最大上传数量." : "最多可以上传" + (message > 1 ? message + "个文件." : "一个文件."));
			return;
		}

		FileProgress(file, this.customSettings.myFileListTarget,this);
		var tr = document.getElementById(file.id);
		tr.style.color="red";
		var bar = document.getElementById(file.id+"_bar");
		var errInfo = "选择失败";

		switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			errInfo = errInfo + ":文件太大";
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			errInfo = errInfo + ":0字节文件";
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			errInfo = errInfo + ":文件类型错误";
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
			default:
			if (file !== null) {
				errInfo = errInfo + ":系统未知错误";
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}

		bar.parentNode.innerHTML=errInfo;
		var delObject = document.getElementById(file.id+"_del");
		delObject.parentNode.innerHTML="&nbsp;";
	} catch (ex) {
		this.debug(ex);
	}
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		/* I want auto start the upload and I can do that here */
		if(this.settings.auto_upload){//是否要上传
			this.startUpload();
		}
	} catch (ex)  {
		this.debug(ex);
	}
}

function uploadStart(file) {
	document.getElementById('album').disabled = true;
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		//进度条
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		var bar = document.getElementById(file.id+"_bar");
		bar.style.width=percent+"px";
		var tr = document.getElementById(file.id);
		tr.style.color="blue";
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	serverData = $.trim(serverData);
	if(serverData == 2) serverData="图片的大小超出限制，请重新上传";
	var isSuccess = (serverData=="kl_album_successed"?true:false);
	try {
		if(isSuccess){
			var tr = document.getElementById(file.id);
			tr.style.color="green";
			var bar = document.getElementById(file.id+"_bar");
			bar.parentNode.innerHTML="上传成功";
			var delObject = document.getElementById(file.id+"_del");
			delObject.parentNode.innerHTML="&nbsp;";
			document.getElementById(this.customSettings.myFileListTarget+"Count").innerHTML=this.getStats().files_queued;
		}else{
			var tr = document.getElementById(file.id);
			tr.style.color="red";
			var bar = document.getElementById(file.id+"_bar");
			bar.parentNode.innerHTML="上传失败:"+serverData;
			var delObject = document.getElementById(file.id+"_del");
			delObject.parentNode.innerHTML="&nbsp;";
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	try {
		switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			//progress.setStatus("Upload Error: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			//progress.setStatus("Upload Failed.");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			//progress.setStatus("Server (IO) Error");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			//progress.setStatus("Security Error");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			//progress.setStatus("Upload limit exceeded.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
			case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			//progress.setStatus("Failed Validation.  Upload skipped.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			// If there aren't any files left (they were all cancelled) disable the cancel button
			break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			//progress.setStatus("Stopped");
			break;
			default:
			//progress.setStatus("Unhandled Error: " + errorCode);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	document.getElementById(this.customSettings.myFileListTarget+"SuccessUploadCount").innerHTML=this.getStats().successful_uploads;//add by stephen
}

// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded) {
	document.getElementById(this.customSettings.myFileListTarget+"SuccessUploadCount").innerHTML=this.getStats().successful_uploads;//add by stephen
	document.getElementById('album').disabled = false;
}

var _K = 1024;
var _M = _K*1024;
function getNiceFileSize(bitnum){
	if(bitnum<_M){
		if(bitnum<_K){
			return bitnum+'B';
		}else{
			return Math.ceil(bitnum/_K)+'K';
		}

	}else{
		return Math.ceil(100*bitnum/_M)/100+'M';
	}
}