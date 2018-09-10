/*
Queue Plug-in

Features:
*Adds a cancelQueue() method for cancelling the entire queue.
*All queued files are uploaded when startUpload() is called.
*If false is returned from uploadComplete then the queue upload is stopped.
If false is not returned (strict comparison) then the queue upload is continued.
*Adds a QueueComplete event that is fired when all the queued files have finished uploading.
Set the event handler with the queue_complete_handler setting.

*/

var SWFUpload;
if (typeof(SWFUpload) === "function") {
	SWFUpload.queue = {};

	SWFUpload.prototype.initSettings = (function (oldInitSettings) {
		return function () {
			if (typeof(oldInitSettings) === "function") {
				oldInitSettings.call(this);
			}

			this.customSettings.queue_cancelled_flag = false;
			this.customSettings.queue_upload_count = 0;

			this.settings.user_upload_complete_handler = this.settings.upload_complete_handler;
			this.settings.upload_complete_handler = SWFUpload.queue.uploadCompleteHandler;

			this.settings.queue_complete_handler = this.settings.queue_complete_handler || null;
		};
	})(SWFUpload.prototype.initSettings);

	SWFUpload.prototype.startUpload = function (fileID) {
		this.customSettings.queue_cancelled_flag = false;
		this.callFlash("StartUpload", false, [fileID]);
	};

	SWFUpload.prototype.cancelQueue = function () {
		this.customSettings.queue_cancelled_flag = true;
		this.stopUpload();

		var stats = this.getStats();
		var fileId;//add by stephen
		while (stats.files_queued > 0) {
			fileId=this.getFile().id;//取出上传队列中的一个File的ID add by stephen
			document.getElementById(fileId+"_del").parentNode.innerHTML="&nbsp;";//add by stephen
			document.getElementById(fileId).style.color="red";//add by stephen
			this.cancelUpload();
			stats = this.getStats();
		}
		document.getElementById(this.customSettings.myFileListTarget+"Count").innerHTML=this.getStats().files_queued;//add by stephen
	};

	SWFUpload.queue.uploadCompleteHandler = function (file) {
		var user_upload_complete_handler = this.settings.user_upload_complete_handler;
		var continueUpload;

		if (file.filestatus === SWFUpload.FILE_STATUS.COMPLETE) {
			this.customSettings.queue_upload_count++;
		}

		if (typeof(user_upload_complete_handler) === "function") {
			continueUpload = (user_upload_complete_handler.call(this, file) === false) ? false : true;
		} else {
			continueUpload = true;
		}

		if (continueUpload) {
			var stats = this.getStats();
			if (stats.files_queued > 0 && this.customSettings.queue_cancelled_flag === false) {
				this.startUpload();
			} else if (this.customSettings.queue_cancelled_flag === false) {
				this.queueEvent("queue_complete_handler", [this.customSettings.queue_upload_count]);
				//this.customSettings.queue_upload_count = 0; comment by stephen
			} else {
				this.customSettings.queue_cancelled_flag = false;
				//this.customSettings.queue_upload_count = 0; comment by stephen
			}
		}
	};
}