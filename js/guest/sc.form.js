/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var form = {
	strength:	function (password)
	{
		var capitalsReg = new RegExp('[A-Z]'), lowersReg = new RegExp('[a-z]'), numbersReg = new RegExp('[0-9]'), specialsReg = new RegExp('([!,%,&,@,#,$,^,*,?,_,~])');

		var characters = (password.length > 5) ? 1 : -1, capitals = (password.match(capitalsReg)) ? 0.5 : 0, lowers = (password.match(lowersReg)) ? 0.5 : 0, numbers = (password.match(numbersReg)) ? 1 : 0, specials = (password.match(specialsReg)) ? 1 : 0, total = (password.length) ? (characters + capitals + lowers + numbers + specials) : -1;
		return total;
	},
	
	validate: function (form)
	{
		var form_id = form.attr('id');
		var status = true;

		$('#' + form_id + ' .validate').each(function(index, element)
		{
			if ($(this).val().length == 0)
			{
				$(this).parent('.validate-group').addClass('has-error has-error-value');
				status = false;
			}

			else
			{
				$(this).parent('.validate-group').removeClass('has-error has-error-value');
			}
		});

		return status;
	}
}