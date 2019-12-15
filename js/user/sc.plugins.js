/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
$(document).ready(function(e){

	initPikaday();
	initMarkdown();

});

function initPikaday()
{
	$('.pikaday').each(function(index, element)
	{
		var now = new Date();
		var el = $(element);
		var picker = new Pikaday({
			field : document.getElementById(el.attr('id')),
			firstDay : 1,
			setDefaultDate : el.val(),
			minDate : new Date('1920-01-01'),
			maxDate : now,
			yearRange : [
					1920, now.getFullYear()
			],
			format : 'DD.MM.YYYY'
		});
	});
}

function initMarkdown()
{
	$(".markdown").markdown({
		additionalButtons : [
			[
				{
					name : 'groupUtil',
					data : [
						{
							name : 'cmdPreview',
							toggle : true,
							hotkey : 'Ctrl+P',
							title : 'Preview',
							btnText : 'Preview',
							btnClass : 'btn-primary',
							icon : {
								glyph : 'fa fa-search',
								fa : 'fa fa-search',
								'fa-3' : 'icon-search'
							},
							callback : function(e)
							{
								// Check the preview mode and toggle based on this flag
								var isPreview = e.$isPreview, content

								if (isPreview == false)
								{
									// Give flag that tell the editor enter preview mode
									e.showPreview()
								}
								else
								{
									e.hidePreview()
								}
							}
						}
					]
				}
			]
		]

	});
}
