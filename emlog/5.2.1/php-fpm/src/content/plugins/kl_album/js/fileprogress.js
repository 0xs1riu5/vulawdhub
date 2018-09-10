/*
A simple class for displaying file information and progress
Note: This is a demonstration only and not part of SWFUpload.
Note: Some have had problems adapting this class in IE7. It may not be suitable for your application.
*/

// Constructor
// file is a SWFUpload file object
// targetID is the HTML element id attribute that the FileProgress HTML structure will be added to.
// Instantiating a new FileProgress object with an existing file will reuse/update the existing DOM elements
/*
*	选择文件后执行的方法.
*	@param file 选择的文件对象，已经经过flash控件处理过了
*	@param fileListID WEB界面显示已选文件信息的位置区域
*	@param swfUploadInstance flash控件对象
*	@reference [handler.js].fileQueued(file)
*	@reference [handler.js].fileQueueError(file)
*/
function FileProgress(file,fileListID,swfUploadInstance) {
	if(!document.getElementById(file.id)){
		document.getElementById(fileListID+"Count").innerHTML=swfUploadInstance.getStats().files_queued;
		var tb = document.getElementById(fileListID);
		var tr = tb.insertRow(-1);
		tr.setAttribute("id",file.id);
		tr.className='uploadTR';
		var td = tr.insertCell(-1);td.className='uploadTD';
		td.innerHTML=file.name;
		td = tr.insertCell(-1);td.className='uploadTD';
		if(file.size){
			td.innerHTML=getNiceFileSize(file.size);
		}else{
			td.innerHTML="0B";
		}
		td = tr.insertCell(-1);td.className='uploadTD';
		td.innerHTML="等待上传<span id="+file.id+"_bar class=uploadBar></span>";
		td = tr.insertCell(-1);td.className='uploadTD';
		td.innerHTML="<span id="+file.id+"_del class=uploadCancel>删除</span>";
		var delObject = document.getElementById(file.id+"_del");
		delObject.onclick = function () {
			swfUploadInstance.cancelUpload(file.id);
			var tr = document.getElementById(file.id);
			document.getElementById('idFileList').deleteRow(tr.rowIndex);
			document.getElementById(fileListID+"Count").innerHTML=swfUploadInstance.getStats().files_queued;
		};
		//alert(tb.innerHTML);
	}
}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";
	this.fileProgressElement.childNodes[3].style.width = "";
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";
};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

// Show/Hide the cancel button
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

// Fades out and clips away the FileProgress box.
FileProgress.prototype.disappear = function () {
	var reduceOpacityBy = 15;
	var reduceHeightBy = 4;
	var rate = 30;	// 15 fps

	if (this.opacity > 0) {
		this.opacity -= reduceOpacityBy;
		if (this.opacity < 0) {
			this.opacity = 0;
		}

		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}

	if (this.height > 0) {
		this.height -= reduceHeightBy;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}

	if (this.height > 0 || this.opacity > 0) {
		var oSelf = this;
		setTimeout(function () {
			oSelf.disappear();
		}, rate);
	} else {
		this.fileProgressWrapper.style.display = "none";
	}
};