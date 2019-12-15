/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var report = {
	image: function (data, callbacks) {
		ajax.connect ({
			url: '/api/image/report',
			type: 'POST',
			data: data,
			dataType: 'json',
			loader: true,
			before: callbacks.before,
			done: function (data) {
				callbacks.done(data);
			},
			error: callbacks.fail,
			always: callbacks.always
		});
	},
	
	user: function (data, callbacks) {
		ajax.connect ({
			url: '/api/user/report',
			type: 'POST',
			data: data,
			dataType: 'json',
			loader: true,
			before: callbacks.before,
			done: function (data) {
				callbacks.done(data);
			},
			error: callbacks.fail,
			always: callbacks.always
		});
	},
	
	block: function (data, callbacks) {
		ajax.connect ({
			url: '/api/user/block',
			type: 'POST',
			data: data,
			dataType: 'json',
			loader: true,
			before: callbacks.before,
			done: function (data) {
				callbacks.done(data);
			},
			error: callbacks.fail,
			always: callbacks.always
		});
	}
}

$(document).ready(function(e)
{
	// Event to report a user
	$(document).on('submit', '#report-user', function(e)
	{
		var id = $(this).attr('data-id');
		var comment = $('#report-comment').val();
		var type = $('#report-type').val();

		$('#report-comment').parent('.form-group').removeClass('has-error');
		if (comment.length > 500)
		{
			$('#report-comment').parent('.form-group').addClass('has-error');
			return;
		}

		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}

		report.user({
			id: id,
			comment: comment,
			type: (type.length == 0) ? 0 : type
		}, callbacks);

		e.preventDefault();
	});

	
	// Event to report an image
	$(document).on('submit', '#report-image', function(e)
	{
		var id = $(this).attr('data-id');
		var comment = $('#report-comment').val();
		var type = $('#report-type').val();

		$('#report-comment').parent('.form-group').removeClass('has-error');
		if (comment.length > 500)
		{
			$('#report-comment').parent('.form-group').addClass('has-error');
			return;
		}

		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}

		report.image({
			id: id,
			comment: comment,
			type: (type.length == 0) ? 0 : type
		}, callbacks);

		e.preventDefault();
	});
	
	// Event to block a user
	$(document).on('click', '.block-user', function(e)
	{
		var id = $(this).attr('data-id');
		confirmDialog("Block User", "Are you sure you want to block this user?", [['class', 'confirm-block-user'], ['data-id', id]]);

		e.preventDefault();
	});
	
	$(document).on('submit', '#confirm-dialog.confirm-block-user', function(e)
	{		
		var id = $(this).attr('data-id');
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}

		report.block({
			id: id
		}, callbacks);
		
		closeDialog();
		e.preventDefault();
	});

	
	
	// Event to unreport user
	$(document).on('click', '.unreport-user', function(e)
	{
		var id = $(this).data('id');
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}

		report.user({
			id: id,
			undo: 'true'
		}, callbacks);
		
		e.preventDefault();
	});

	
	
	// Event to unreport image
	$(document).on('click', '.unreport-image', function(e)
	{
		var id = $(this).data('id');
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}

		report.image({
			id: id,
			undo: 'true'
		}, callbacks);

		e.preventDefault();
	});

	
	
	// Event to unblock user
	$(document).on('click', '.unblock-user', function(e)
	{
		var id = $(this).data('id');
		var callbacks = ajax.callbacks();
		callbacks.done = function (data) {
			if (typeof data.error === 'undefined')
			{
				functions.toast(data.success, true);
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
			else
			{
				functions.toast(data.error, false);
			}
		}

		report.block({
			id: id,
			undo: 'true'
		}, callbacks);
		
		e.preventDefault();
	});
	
});