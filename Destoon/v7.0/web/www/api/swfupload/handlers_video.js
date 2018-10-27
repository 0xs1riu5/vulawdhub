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
function fileQueued(file) {
	try {
		this.customSettings.tdFilesQueued.innerHTML = this.getStats().files_queued;
	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete() {
	this.startUpload();
}

function uploadStart(file) {
	try {
		this.customSettings.progressCount = 0;
		updateDisplay.call(this, file);
	}
	catch (ex) {
		this.debug(ex);
	}
	
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		this.customSettings.progressCount++;
		updateDisplay.call(this, file);
	} catch (ex) {
		this.debug(ex);
	}
	
}

function uploadSuccess(file, serverData) {
	try {
		updateDisplay.call(this, file);
		//DT ADD
		if (serverData.substring(0, 7) === "FILEID:") {
			Dd('video').value = serverData.substring(7);
			Dd('tdPercentUploaded').innerHTML = '100%';
		} else {
			Dd('tdPercentUploaded').innerHTML = '0%';
			if(serverData.indexOf('Kb)') != -1) {
				Dd('tdPercentUploaded').innerHTML = '上传失败，文件过大';
			} else if(serverData.indexOf('Denied') != -1) {
				Dd('tdPercentUploaded').innerHTML = '上传失败，未知错误';
			} else {
				Dd('tdPercentUploaded').innerHTML = '上传失败，请重试';
				//alert('上传失败'+serverData);
			}
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	this.customSettings.tdFilesQueued.innerHTML = this.getStats().files_queued;
	this.customSettings.tdFilesUploaded.innerHTML = this.getStats().successful_uploads;
	this.customSettings.tdErrors.innerHTML = this.getStats().upload_errors;

}

function updateDisplay(file) {
	this.customSettings.tdCurrentSpeed.innerHTML = SWFUpload.speed.formatBPS(file.currentSpeed);
	this.customSettings.tdAverageSpeed.innerHTML = SWFUpload.speed.formatBPS(file.averageSpeed);
	this.customSettings.tdMovingAverageSpeed.innerHTML = SWFUpload.speed.formatBPS(file.movingAverageSpeed);
	this.customSettings.tdTimeRemaining.innerHTML = SWFUpload.speed.formatTime(file.timeRemaining);
	this.customSettings.tdTimeElapsed.innerHTML = SWFUpload.speed.formatTime(file.timeElapsed);
	this.customSettings.tdPercentUploaded.innerHTML = SWFUpload.speed.formatPercent(file.percentUploaded);
	this.customSettings.tdSizeUploaded.innerHTML = SWFUpload.speed.formatBytes(file.sizeUploaded);
	this.customSettings.tdProgressEventCount.innerHTML = this.customSettings.progressCount;

}